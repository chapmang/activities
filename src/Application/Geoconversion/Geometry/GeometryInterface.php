<?php
declare(strict_types=1);
namespace App\Application\GeoConversion\Geometry;

interface GeometryInterface
{
    public function toGeoJSON();

    public function toKML();

    public function toWKT();

    public function toGPX($mode = null);

    public function equals(Geometry $geom);
}