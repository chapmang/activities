<?php
declare(strict_types=1);
namespace App\Application\GeoConversion;

use App\Application\GeoConversion\Decoder\GeoDecoderStrategyInterface;

/**
 * Class GeoEncode
 * @package App\Application\GeoConversion
 */
class GeoDecode
{
    private $strategies;

    /**
     * @param GeoDecoderStrategyInterface $strategy
     */
    public function addStrategy(GeoDecoderStrategyInterface $strategy): void
    {
        $this->strategies[] = $strategy;
    }

    /**
     * @param string $format
     * @param $geometry
     * @return mixed
     */
    public function decodeGeometry(string $format, $geometry)
    {
        foreach ($this->strategies as $strategy) {
            if ($strategy->isDecodable($format)) {
                return $strategy->decode($geometry);
            }
        }
    }
}