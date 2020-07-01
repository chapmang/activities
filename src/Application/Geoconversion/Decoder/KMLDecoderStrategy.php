<?php
declare(strict_types=1);
namespace App\Application\GeoConversion\Decoder;

use App\Application\GeoConversion\Exception\InvalidText;
use App\Application\GeoConversion\Geometry\LinearRing;
use App\Domain\DataTypes\Spatial\Types\Geography\Point;

final class KMLDecoderStrategy extends XMLDecoderStrategy
{
    private $key = 'kml';

    public function isDecodable(string $format): bool
    {
        return $format === $this->key;
    }

    protected function parsePoint($xml) {
        $coordinates = $this->_extractCoordinates($xml);
        $coords = preg_split('/,/', (string)$coordinates[0]);
        return array_map("trim", $coords);
    }

     protected function parseLineString($xml) {
        $coordinates = $this->_extractCoordinates($xml);
        foreach (preg_split('/\s+/', trim((string)$coordinates[0])) as $compstr) {
            $coords = preg_split('/,/', $compstr);
            $components[] = new Point($coords);
        }
        return $components;
    }

    protected function parseLinearRing($xml) {
        return $this->parseLineString($xml);
    }

    protected function parsePolygon($xml) {
        $ring = array();
        foreach ($this->childElements($xml, 'outerboundaryis') as $elem) {
            $ring = array_merge($ring, $this->childElements($elem, 'linearring'));
        }

        if (count($ring) != 1) {
            throw new InvalidText(__CLASS__);
        }

        $components = array(new LinearRing($this->parseLinearRing($ring[0])));
        foreach ($this->childElements($xml, 'innerboundaryis') as $elem) {
            foreach ($this->childElements($elem, 'linearring') as $ring) {
                $components[] = new LinearRing($this->parseLinearRing($ring[0]));
            }
        }
        return $components;
    }

    protected function parseMultiGeometry($xml) {
        $components = array();
        foreach ($xml->children() as $child) {
            $components[] = $this->_geomFromXML($child);
        }
        return $components;
    }

    protected function _extractCoordinates($xml) {
        $coordinates = $this->childElements($xml, 'coordinates');
        if (count($coordinates) != 1) {
            throw new InvalidText(__CLASS__);
        }
        return $coordinates;
    }

    protected function _geomFromXML($xml) {
        $nodename = strtolower($xml->getName());
        if ($nodename == "kml" or $nodename == "document" or $nodename == "placemark") {
            return $this->_childsCollect($xml);
        }

        foreach (array("Point", "LineString", "LinearRing", "Polygon", "MultiGeometry") as $kml_type) {
            if (strtolower($kml_type) == $nodename) {
                $type = $kml_type;
                break;
            }
        }

        if (!isset($type)) {
            throw new InvalidText(__CLASS__);
        }

        try {
            $components = call_user_func(array('static', 'parse'.$type), $xml);
        } catch(InvalidText $e) {
            throw new InvalidText(__CLASS__);
        } catch(\Exception $e) {
            throw $e;
        }

        if ($type == "MultiGeometry") {
            if (count($components)) {
                $possibletype = $components[0]::name;
                $sametype = true;
                foreach (array_slice($components, 1) as $component) {
                    if ($component::name != $possibletype) {
                        $sametype = false;
                        break;
                    }
                }
                if ($sametype) {
                    switch ($possibletype) {
                        case "Point":
                            return new MultiPoint($components);
                            break;
                        case "LineString":
                            return new MultiLineString($components);
                            break;
                        case "Polygon":
                            return new MultiPolygon($components);
                            break;
                        default:
                            break;
                    }
                }
            }
            return new GeometryCollection($components);
        }

        return $this->geographyFactory->{'create'. $type}($components);
    }
}