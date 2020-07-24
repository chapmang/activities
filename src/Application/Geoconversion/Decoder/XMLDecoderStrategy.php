<?php
declare(strict_types=1);
namespace App\Application\GeoConversion\Decoder;

use App\Application\GeoConversion\Exception\InvalidText;
use App\Application\GeoConversion\Exception\UnavailableResource;
use App\Application\GeoConversion\Geometry\GeometryCollection;
use App\Domain\Services\GeographyFactory;

abstract class XMLDecoderStrategy implements GeoDecoderStrategyInterface
{
    protected $geographyFactory;

    public function __construct(GeographyFactory $geometryFactory)
    {
        $this->geographyFactory = $geometryFactory;
    }

    /**
     * @param $text
     * @throws InvalidText
     * @throws UnavailableResource
     */
    public function decode($text) {
        if (!function_exists("simplexml_load_string") || !function_exists("libxml_use_internal_errors")) {
            throw new UnavailableResource("simpleXML");
        }
        libxml_use_internal_errors(true);
        $xmlobj = simplexml_load_string($text);
        if ($xmlobj === false) {
            throw new InvalidText(__CLASS__, $text);
        }

        try {
            $geom = $this->_geomFromXML($xmlobj);
        } catch(InvalidText $e) {
            throw new InvalidText(__CLASS__, $text);
        } catch(\Exception $e) {
            throw $e;
        }

        return $geom;
    }

    protected function childElements($xml, $nodename = "") {
        $nodename = strtolower($nodename);
        $res = array();
        foreach ($xml->children() as $child) {
            if ($nodename) {
                if (strtolower($child->getName()) == $nodename) {
                    array_push($res, $child);
                }
            } else {
                array_push($res, $child);
            }
        }
        return $res;
    }

    protected function _childsCollect($xml) {
        $components = array();
        foreach ($this->childElements($xml) as $child) {
            try {
                $geom = $this->_geomFromXML($child);
                $components[] = $geom;
            } catch(InvalidText $e) {
            }
        }

        $ncomp = count($components);
        if ($ncomp == 0) {
            throw new InvalidText(__CLASS__);
        } else if ($ncomp == 1) {
            return $components[0];
        } else {
            return new GeometryCollection($components);
        }
    }

    protected function _geomFromXML($xml) {}
}