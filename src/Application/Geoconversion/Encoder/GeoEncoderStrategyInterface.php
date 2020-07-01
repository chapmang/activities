<?php
declare(strict_types=1);
namespace App\Application\GeoConversion\Encoder;

interface GeoEncoderStrategyInterface
{
    public const SERVICE_TAG = 'geoencoder_strategy';

    public function isEncodable(string $format): bool;
    public function encode($object, $name, $mode);
}