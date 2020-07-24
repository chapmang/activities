<?php
declare(strict_types=1);
namespace App\Application\GeoConversion\Geometry;

use App\Application\GeoConversion\Exception\InvalidFeature;

class MultiLineString extends Collection
{
    const name = "MultiLineString";

    /**
     * MultiLineString constructor.
     * @param $components
     * @throws InvalidFeature
     */
    public function __construct($components) {
        foreach ($components as $comp) {
            if (!($comp instanceof LineString)) {
                throw new InvalidFeature(__CLASS__, "MultiLineString can only contain LineString elements");
            }
        }
        $this->components = $components;
    }
}