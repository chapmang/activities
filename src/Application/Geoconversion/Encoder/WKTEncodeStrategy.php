<?php
declare(strict_types=1);
namespace App\Application\GeoConversion\Encoder;

use App\Domain\DataTypes\Spatial\Types\Geography\Point;

final class WKTEncodeStrategy implements GeoEncoderStrategyInterface
{
    private $key = 'wkt';

    /**
     * @param string $format
     * @return bool
     */
    public function isEncodable(string $format): bool
    {
        return $format === $this->key;
    }

    /**
     * @param $geom
     * @param null $name
     * @param null $mode
     * @return string
     */
    public function encode($geom, $name = null, $mode = null)
    {
        if ($geom instanceof Point)
            return strtoupper($geom::name) . "({$geom->getLongitude()} {$geom->getLatitude()})";

        $recursiveWKT = function ($geom) use (&$recursiveWKT) {
            if ($geom instanceof Point) {
                return "{$geom->getLongitude()} {$geom->getLatitude()}";
            } else {
                return "(" . implode (',', array_map($recursiveWKT, $geom->getPoints())). ")";
            }
        };
        return strtoupper($geom::name) . call_user_func($recursiveWKT, $geom);
    }
}