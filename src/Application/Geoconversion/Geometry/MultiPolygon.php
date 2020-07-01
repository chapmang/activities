<?php
declare(strict_types=1);
namespace App\Application\GeoConversion\Geometry;

use App\Application\GeoConversion\Exception\InvalidFeature;

class MultiPolygon extends Collection
{
    const name = "MultiPolygon";

    /**
     * MultiPolygon constructor.
     * @param $components
     * @throws InvalidFeature
     */
    public function __construct($components) {
        foreach ($components as $comp) {
            if (!($comp instanceof Polygon)) {
                throw new InvalidFeature(__CLASS__, "MultiPolygon can only contain Polygon elements");
            }
        }
        $this->components = $components;
    }
}