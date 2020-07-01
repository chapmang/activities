<?php
declare(strict_types=1);
namespace App\Application\GeoConversion\Geometry;

use App\Application\GeoConversion\Exception\InvalidFeature;
use App\Domain\DataTypes\Spatial\Types\Geography\Point;

class Multipoint extends Collection
{
    const name = "MultiPoint";

    /**
     * Multipoint constructor.
     * @param $components
     * @throws InvalidFeature
     */
    public function __construct($components) {
        foreach ($components as $comp) {
            if (!($comp instanceof Point)) {
                throw new InvalidFeature(__CLASS__, static::name . " can only contain Point elements");
            }
        }
        $this->components = $components;
    }

    public function equals(Geometry $geom) {
        if (get_class ($geom) != get_class($this)) {
            return false;
        }
        if (count($this->components) != count($geom->components)) {
            return false;
        }
        foreach (range(0, count($this->components) - 1) as $count) {
            if (!$this->components[$count]->equals($geom->components[$count])) {
                return false;
            }
        }
        return true;
    }
}