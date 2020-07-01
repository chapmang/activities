<?php
declare(strict_types=1);
namespace App\Application\GeoConversion\Encoder;

use App\Domain\DataTypes\Spatial\Types\Geography\Point;

final class KMLEncodeStrategy implements GeoEncoderStrategyInterface
{
    private $key = 'kml';

    /**
     * @param string $format
     * @return bool
     */
    public function isEncodable(string $format): bool
    {
        return $format === $this->key;
    }


    public function encode($geom, $name = null, $mode = null): string
    {
        if (!$name) {
            $encodedName = "<name/>";
        } else {
            $encodedName = "<name>{$name}</name>";
        }

        if ($geom instanceof Point) {
            $encodedGeometry = "<Point><coordinates>{$geom->getLongitude()},{$geom->getLatitude()}</coordinates></Point>";
        } else {
            $encodedGeometry =  "<" . $geom::name . "><coordinates>" . implode(" ", array_map(function($comp) {
                    return "{$comp->getLongitude()},{$comp->getLatitude()}";
                }, $geom->getPoints())). "</coordinates></" . $geom::name . ">";
        }

        $kml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>"
        ."<kml xmlns=\"http://earth.google.com/kml/2.0\">"
        ."<Placemark>"
        . $encodedName
        . $encodedGeometry
        ."</Placemark>"
        ."</kml>";

        return $kml;

    }
}