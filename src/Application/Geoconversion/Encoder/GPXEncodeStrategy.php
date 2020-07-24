<?php
declare(strict_types=1);
namespace App\Application\GeoConversion\Encoder;

use App\Domain\DataTypes\Spatial\Types\Geography\Point;

final class GPXEncodeStrategy implements GeoEncoderStrategyInterface
{
    private $key = 'gpx';

    /**
     * @param string $format
     * @return bool
     */
    public function isEncodable(string $format): bool
    {
        return $format === $this->key;
    }


    public function encode($geom, $mode = null, $name = null): string
    {
        if(!$name) {
            $metadata = '';
            $encodedName = '';
        } else {
            $encodedName = "<name>{$name}</name>";
            $metadata = "<metadata>{$encodedName}</metadata>";
        }
        if ($geom instanceof Point) {
            $encodedGeometry = "<wpt lon=\"{$geom->getLongitude()}\" lat=\"{$geom->getLatitude()}\" ></wpt >";
        } else if ($mode == "trkseg") {
            $encodedGeometry = '<trkseg>'. $encodedName . implode ("", array_map(function ($comp) {
                    return "<trkpt lon=\"{$comp->getLongitude()}\" lat=\"{$comp->getLatitude()}\"></trkpt>";
                }, $geom->getPoints())). "</trkseg>";
        } else {
            $encodedGeometry = '<rte>'. $encodedName . implode ("", array_map(function ($comp) {
                    return "<rtept lon=\"{$comp->getLongitude()}\" lat=\"{$comp->getLatitude()}\"></rtept>";
                }, $geom->getPoints())). "</rte>";
        }


        $kml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>"
            . "<gpx version=\"1.1\" creator=\"Activities-AA Media\" "
            . "xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" "
            . "xsi:schemaLocation=\"http://www.topografix.com/GPX/1/1 http://www.topografix.com/GPX/1/1/gpx.xsd\">"
            . $metadata
            . $encodedGeometry
            . " </gpx>";

        return $kml;

    }
}