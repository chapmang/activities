<?php
declare(strict_types=1);
namespace App\Application\GeoConversion\Geometry;

use App\Application\GeoConversion\Exception\InvalidFeature;
use App\Application\GeoConversion\Exception\RangeException;
use App\Application\GeoConversion\Exception\UnimplementedMethod;
use Exception;

class Point extends Geometry
{
    const name = "Point";

    private $lon;
    private $lat;

    /**
     * Point constructor.
     * @param $coords
     * @throws InvalidFeature
     */
    public function __construct($coords) {
        if (count ($coords) < 2) {
            throw new InvalidFeature(__CLASS__, "Point must have two coordinates");
        }
        $lon = $coords[0];
        $lat = $coords[1];
        if (!$this->checkLon($lon)) {
            throw new RangeException();
        }
        if (!$this->checkLat($lat)) {
            throw new RangeException();
        }
        $this->lon = (float)$lon;
        $this->lat = (float)$lat;
    }

    /**
     * @param $property
     * @return float
     * @throws Exception
     */
    public function __get($property) {
        if ($property == "lon") {
            return $this->lon;
        } else if ($property == "lat") {
            return $this->lat;
        } else {
            throw new Exception ("Undefined property");
        }
    }

    public function toWKT() {
        return strtoupper(static::name) . "({$this->lon} {$this->lat})";
    }

    public function toKML() {
        return "<" . static::name . "><coordinates>{$this->lon},{$this->lat}</coordinates></" . static::name . ">";
    }

    public function toGPX($mode = null) {
        if (!$mode) {
            $mode = "wpt";
        }
        if ($mode != "wpt") {
            throw new UnimplementedMethod(__FUNCTION__, get_called_class());
        }
        return "<wpt lon=\"{$this->lon}\" lat=\"{$this->lat}\"></wpt>";
    }

    public function toGeoJSON() {
        $value = (object)array ('type' => static::name, 'coordinates' => array($this->lon, $this->lat));
        return json_encode($value);
    }

    public function equals(Geometry $geom) {
        if (get_class ($geom) != get_class($this)) {
            return false;
        }
        return $geom->lat == $this->lat && $geom->lon == $this->lon;
    }

    private function checkLon($lon) {
        if (!is_numeric($lon)) {
            return false;
        }
        if ($lon < -180 || $lon > 180) {
            return false;
        }
        return true;
    }
    private function checkLat($lat) {
        if (!is_numeric($lat)) {
            return false;
        }
        if ($lat < -90 || $lat > 90) {
            return false;
        }
        return true;
    }
}