<?php
declare(strict_types=1);
namespace App\Domain\Services;

use App\Domain\DataTypes\Spatial\Exception\InvalidValueException;
use App\Domain\DataTypes\Spatial\Types\Geography\LineString;
use App\Domain\DataTypes\Spatial\Types\Geography\Point;
use App\Domain\DataTypes\Spatial\Types\Geography\Polygon;

class GeographyFactory
{

    public function __construct(){}

    /**
     * @param $coordinates
     * @return Point
     * @throws InvalidValueException
     */
    public function createPoint($coordinates)
    {
        return new Point($coordinates);
    }

    /**
     * @param $points
     * @return LineString
     * @throws InvalidValueException
     */
    public function createLineString($points)
    {
        return new LineString($points);
    }

    /**
     * @param $text
     * @return Polygon
     * @throws InvalidValueException
     */
    public function createPolygon($text)
    {
        return new Polygon($text);
    }
}