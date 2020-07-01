<?php
declare(strict_types=1);
namespace App\Application\GeoConversion\Decoder;

use App\Application\GeoConversion\Exception\InvalidText;
use App\Domain\DataTypes\Spatial\Exception\InvalidValueException;
use App\Domain\DataTypes\Spatial\Types\Geography\Point;
use App\Domain\Services\GeographyFactory;

final class GeoJSONDecoderStrategy implements GeoDecoderStrategyInterface
{
    private $key = 'json';

    private $geographyFactory;

    public function __construct(GeographyFactory $geometryFactory)
    {
        $this->geographyFactory = $geometryFactory;
    }

    public function isDecodable(string $format): bool
    {
        return $format === $this->key;
    }

    /**
     * @param $text
     * @return mixed
     * @throws InvalidText
     */
    public function decode($text)
    {
        $ltext = strtolower($text);
        $obj = json_decode($ltext);
        if (is_null ($obj)) {
            throw new InvalidText(__CLASS__, $text);
        }

        try {
            $geom = $this->_geomFromJson($obj);
        } catch(InvalidText $e) {
            throw new InvalidText(__CLASS__, $text);
        } catch(\Exception $e) {
            throw $e;
        }

        return $geom;
    }

    /**
     * @param $json
     * @return mixed
     * @throws InvalidText
     */
    protected function _geomFromJson($json)
    {
        if (property_exists ($json, "geometry") and is_object($json->geometry)) {
            return $this->_geomFromJson($json->geometry);
        }

        if (!property_exists ($json, "type") or !is_string($json->type)) {
            throw new InvalidText(__CLASS__);
        }

        foreach (array("Point", "MultiPoint", "LineString", "MultiLinestring", "LinearRing",
                     "Polygon", "MultiPolygon", "GeometryCollection") as $json_type) {
            if (strtolower($json_type) == $json->type) {
                $type = $json_type;
                break;
            }
        }

        if (!isset($type)) {
            throw new InvalidText(__CLASS__);
        }

        try {
            $components = $this->{'parse'.$type}($json);
            //$components = call_user_func(array('static', 'parse'.$type), $json);
        } catch(InvalidText $e) {
            throw new InvalidText(__CLASS__);
        } catch(\Exception $e) {
            throw $e;
        }

        return $this->geographyFactory->{'create'.$type}($components);
    }

    /**
     * @param $json
     * @return array
     * @throws InvalidText
     */
    protected function parsePoint($json)
    {
        if (!property_exists ($json, "coordinates") or !is_array($json->coordinates)) {
            throw new InvalidText(__CLASS__);
        }
        return $json->coordinates;
    }

    /**
     * @param $json
     * @return Point[]|array
     * @throws InvalidText
     */
    protected function parseMultiPoint($json)
    {
        if (!property_exists ($json, "coordinates") or !is_array($json->coordinates)) {
            throw new InvalidText(__CLASS__);
        }
        return array_map(function($coords) {
            return $this->geographyFactory->createPoint($coords);;
        }, $json->coordinates);
    }

    /**
     * @param $json
     * @return Point[]|array
     * @throws InvalidText
     */
    protected function parseLineString($json)
    {
        return $this->parseMultiPoint($json);
    }

    /**
     * @param $json
     * @return array
     * @throws InvalidText
     * @throws InvalidValueException
     */
    protected function parseMultiLineString($json)
    {
        $components = array();
        if (!property_exists ($json, "coordinates") or !is_array($json->coordinates)) {
            throw new InvalidText(__CLASS__);
        }
        foreach ($json->coordinates as $coordinates) {
            $linecomp = array();
            foreach ($coordinates as $coordPair) {
                $linecomp[] = $this->geographyFactory->createPoint($coordPair);
            }
            $components[] = $this->geographyFactory->createLineString($linecomp);
        }
        return $components;
    }

    /**
     * @param $json
     * @return Point[]|array
     * @throws InvalidText
     */
    protected function parseLinearRing($json)
    {
        return $this->parseMultiPoint($json);
    }

    /**
     * @param $json
     * @return array
     * @throws InvalidText
     * @throws InvalidValueException
     */
    protected function parsePolygon($json)
    {
        $components = array();
        if (!property_exists ($json, "coordinates") or !is_array($json->coordinates)) {
            throw new InvalidText(__CLASS__);
        }
        foreach ($json->coordinates as $coordinates) {
            $ringcomp = array();
            foreach ($coordinates as $coordPair) {
                $ringcomp[] = $this->geographyFactory->createPoint($coordPair);
            }
            $components[] = $this->geographyFactory->createLinearRing($ringcomp);
        }
        return $components;
    }

    /**
     * @param $json
     * @return array
     * @throws InvalidText
     * @throws InvalidValueException
     */
    protected function parseMultiPolygon($json)
    {
        $components = array();
        if (!property_exists ($json, "coordinates") or !is_array($json->coordinates)) {
            throw new InvalidText(__CLASS__);
        }
        foreach ($json->coordinates as $coordinates) {
            $polycomp = array();
            foreach ($coordinates as $coordinates) {
                $ringcomp = array();
                foreach ($coordinates as $coordinates) {
                    $ringcomp[] = $this->geographyFactory->createPoint($coordinates);
                }
                $polycomp[] = $this->geographyFactory->createLinearRing($ringcomp);
            }
            $components[] = $this->geographyFactory->createPolygon($polycomp);
        }
        return $components;
    }

    /**
     * @param $json
     * @return array
     * @throws InvalidText
     */
    protected function parseGeometryCollection($json)
    {
        if (!property_exists ($json, "geometries") or !is_array($json->geometries)) {
            throw new InvalidText(__CLASS__);
        }
        $components = array();
        foreach ($json->geometries as $geometry) {
            $components[] = $this->_geomFromJson($geometry);
        }

        return $components;
    }
}