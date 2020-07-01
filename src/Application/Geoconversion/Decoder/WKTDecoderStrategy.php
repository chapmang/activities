<?php
declare(strict_types=1);
namespace App\Application\GeoConversion\Decoder;

use App\Application\GeoConversion\Exception\InvalidText;
use App\Domain\DataTypes\Spatial\Types\Geography\Point;
use App\Domain\Services\GeographyFactory;

final class WKTDecoderStrategy implements GeoDecoderStrategyInterface
{
    private $key = 'wkt';

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
        $type_pattern = '/\s*(\w+)\s*\(\s*(.*)\s*\)\s*$/';
        if (!preg_match($type_pattern, $ltext, $matches)) {
            throw new InvalidText(__CLASS__, $text);
        }
        foreach (array("Point", "MultiPoint", "LineString", "MultiLinestring", "LinearRing",
                     "Polygon", "MultiPolygon", "GeometryCollection") as $wkt_type) {
            if (strtolower($wkt_type) == $matches[1]) {
                $type = $wkt_type;
                break;
            }
        }

        if (!isset($type)) {
            throw new InvalidText(__CLASS__, $text);
        }

        try {
            $components = $this->{'parse'.$type}($matches[2]);
            //$components = call_user_func(array(__NAMESPACE__, 'parse' . $type), $matches[2]);
        } catch (InvalidText $e) {
            throw new InvalidText(__CLASS__, $text);
        } catch (\Exception $e) {
            throw $e;
        }

        return $this->geographyFactory->{'create'.$type}($components);
    }

    public function coordinatesFromText(){

    }

    protected function parsePoint($str) {
        return preg_split('/\s+/', trim($str));
    }

    protected function parseMultiPoint($str) {
        $str = trim($str);
        if (strlen ($str) == 0) {
            return array();
        }
        return $this->parseLineString($str);
    }

    protected function parseLineString($str) {
        $components = array();
        foreach (preg_split('/,/', trim($str)) as $compstr) {
            $components[] = new Point($this->parsePoint($compstr));
        }
        return $components;
    }

    protected function parseMultiLineString($str) {
        return $this->_parseCollection($str, "LineString");
    }

    protected function parseLinearRing($str) {
        return $this->parseLineString($str);
    }

    protected function parsePolygon($str) {
        return $this->_parseCollection($str, "LinearRing");
    }

    protected function parseMultiPolygon($str) {
        return $this->_parseCollection($str, "Polygon");
    }

    protected function parseGeometryCollection($str) {
        $components = array();
        foreach (preg_split('/,\s*(?=[A-Za-z])/', trim($str)) as $compstr) {
            $components[] = $this->decode($compstr);
        }
        return $components;
    }

    protected function _parseCollection($str, $child_constructor) {
        $components = array();
        foreach (preg_split('/\)\s*,\s*\(/', trim($str)) as $compstr) {
            if (strlen($compstr) and $compstr[0] == '(') {
                $compstr = substr($compstr, 1);
            }
            if (strlen($compstr) and $compstr[strlen($compstr)-1] == ')') {
                $compstr = substr($compstr, 0, -1);
            }

            $childs = call_user_func(array('static', 'parse' . $child_constructor), $compstr);
            $constructor = "App\Application\Geoconversion\Geometry" . '\\' . $child_constructor;
            $components[] = new $constructor($childs);
        }
        return $components;
    }
}