<?php
declare(strict_types=1);
namespace App\Application\GeoConversion\Geometry;

use App\Application\GeoConversion\Exception\InvalidFeature;

class Polygon extends Collection
{
    const name = "Polygon";

    /**
     * Polygon constructor.
     * @param $components
     * @throws InvalidFeature
     */
    public function __construct($components) {
        $outer = $components[0];
        foreach (array_slice($components, 1) as $inner) {
            if (!$outer->contains($inner)) {
                throw new InvalidFeature(__CLASS__, "Polygon inner rings must be enclosed in outer ring");
            }
        }
        foreach ($components as $comp) {
            if (!($comp instanceof LinearRing)) {
                throw new InvalidFeature(__CLASS__, "Polygon can only contain LinearRing elements");
            }
        }
        $this->components = $components;
    }

    public function toKML() {
        $str = '<outerBoundaryIs>' . $this->components[0]->toKML() . '</outerBoundaryIs>';
        $str .= implode("", array_map(function($comp) {
            return '<innerBoundaryIs>' . $comp->toKML() . '</innerBoundaryIs>';
        }, array_slice($this->components, 1)));
        return '<' . static::name . '>' . $str . '</' . static::name . '>';
    }
}