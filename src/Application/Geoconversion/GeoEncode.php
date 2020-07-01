<?php
declare(strict_types=1);
namespace App\Application\GeoConversion;

use App\Application\GeoConversion\Encoder\GeoEncoderStrategyInterface;

/**
 * Class GeoEncode
 * @package App\Application\GeoConversion
 */
class GeoEncode
{
    private $strategies;

    /**
     * @param GeoEncoderStrategyInterface $strategy
     */
    public function addStrategy(GeoEncoderStrategyInterface $strategy): void
    {
        $this->strategies[] = $strategy;
    }

    /**
     * @param string $format
     * @param $geometry
     * @param null $mode
     * @param null $name
     * @return mixed
     */
    public function encodeGeometry(string $format, $geometry, $name = null, $mode = null)
    {
        foreach ($this->strategies as $strategy) {
            if ($strategy->isEncodable($format)) {
                return $strategy->encode($geometry, $name, $mode);
            }
        }
    }
}