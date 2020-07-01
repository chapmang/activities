<?php
declare(strict_types=1);
namespace App\Application\GeoConversion\Geometry;

use App\Application\GeoConversion\Exception\UnimplementedMethod;

class Geometry implements GeometryInterface
{
    const name = "";

    /**
     * @throws UnimplementedMethod
     */
    public function toGeoJSON()
    {
        throw new UnimplementedMethod(__FUNCTION__, get_called_class());
    }

    /**
     * @throws UnimplementedMethod
     */
    public function toKML()
    {
        throw new UnimplementedMethod(__FUNCTION__, get_called_class());
    }

    /**
     * @throws UnimplementedMethod
     */
    public function toWKT()
    {
        throw new UnimplementedMethod(__FUNCTION__, get_called_class());
    }

    /**
     * @param null $mode
     * @throws UnimplementedMethod
     */
    public function toGPX($mode = null)
    {
        throw new UnimplementedMethod(__FUNCTION__, get_called_class());
    }

    /**
     * @param Geometry $geom
     * @throws UnimplementedMethod
     */
    public function equals(Geometry $geom)
    {
        throw new UnimplementedMethod(__FUNCTION__, get_called_class());
    }

    /**
     * @throws UnimplementedMethod
     */
    public function __toString()
    {
        return $this->toWKT();
    }
}