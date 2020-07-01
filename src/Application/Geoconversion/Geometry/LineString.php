<?php
declare(strict_types=1);
namespace App\Application\GeoConversion\Geometry;

use App\Application\GeoConversion\Exception\InvalidFeature;
use App\Application\GeoConversion\Exception\UnimplementedMethod;

class LineString extends Multipoint
{
    const name = "LineString";
    public function __construct($components) {
        if (count ($components) < 2) {
            throw new InvalidFeature(__CLASS__, "LineString must have at least 2 points");
        }
        parent::__construct($components);
    }

    public function toKML() {
        return "<" . static::name . "><coordinates>" . implode(" ", array_map(function($comp) {
                return "{$comp->lon},{$comp->lat}";
            }, $this->components)). "</coordinates></" . static::name . ">";
    }

    public function toGPX($mode = null, $name = null) {
        if (!$mode) {
            $mode = "trkseg";
        }
        if ($name) {
            $nameString = "<name>$name</name>";
        } else {
            $nameString = "<name/>";
        }
        if ($mode != "trkseg" and $mode != "rte") {
            throw new UnimplementedMethod(__FUNCTION__, get_called_class());
        }
        if ($mode == "trkseg") {
            return '<trkseg>'. $nameString . implode ("", array_map(function ($comp) {
                    return "<trkpt lon=\"{$comp->lon}\" lat=\"{$comp->lat}\"></trkpt>";
                }, $this->components)). "</trkseg>";
        } else {
            return '<rte>'. $nameString . implode ("", array_map(function ($comp) {
                    return "<rtept lon=\"{$comp->lon}\" lat=\"{$comp->lat}\"></rtept>";
                }, $this->components)). "</rte>";
        }

    }
}