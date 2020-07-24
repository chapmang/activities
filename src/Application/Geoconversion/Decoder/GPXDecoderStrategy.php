<?php
declare(strict_types=1);
namespace App\Application\GeoConversion\Decoder;

use App\Application\GeoConversion\Exception\InvalidText;
use App\Domain\DataTypes\Spatial\Types\Geography\Point;

final class GPXDecoderStrategy extends XMLDecoderStrategy
{
    private $key = 'gpx';

    public function isDecodable(string $format): bool
    {
        return $format === $this->key;
    }

    protected function _extractCoordinates($xml) {
        $attributes = $xml->attributes();
        $lon = (string) $attributes['lon'];
        $lat = (string) $attributes['lat'];
        if (!$lon or !$lat) {
            throw new InvalidText(__CLASS__);
        }
        return array($lon, $lat);
    }

    protected function parseTrkseg($xml) {
        $res = array();
        foreach ($xml->children() as $elem) {
            if (strtolower($elem->getName()) == "trkpt") {
                $res[] = new Point($this->_extractCoordinates($elem));
            }
        }
        return $res;
    }

    protected function parseRte($xml) {
        $res = array();
        foreach ($xml->children() as $elem) {
            if (strtolower($elem->getName()) == "rtept") {
                $res[] = new Point($this->_extractCoordinates($elem));
            }
        }
        return $res;
    }

    protected function parseWpt($xml) {
        return $this->_extractCoordinates($xml);
    }

    protected function _geomFromXML($xml) {
        $nodename = strtolower($xml->getName());
        if ($nodename == "gpx" or $nodename == "trk") {
            return $this->_childsCollect($xml);
        }
        foreach (array("Trkseg", "Rte", "Wpt") as $kml_type) {
            if (strtolower($kml_type) == $xml->getName()) {
                $type = $kml_type;
                break;
            }
        }

        if (!isset($type)) {
            throw new InvalidText(__CLASS__);
        }

        try {
            $components = $this->{'parse'.$type}($xml);
            //$components = call_user_func(array('static', 'parse'.$type), $xml);
        } catch(InvalidText $e) {
            throw new InvalidText(__CLASS__);
        } catch(\Exception $e) {
            throw $e;
        }

        if ($type == "Trkseg" or $type == "Rte") {
            $constructor = "createLineString";
        } else if ($type == "Wpt") {
            $constructor = "createPoint";
        }

        return $this->geographyFactory->{$constructor}($components);
    }
}