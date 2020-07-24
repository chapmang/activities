<?php
declare(strict_types=1);
namespace App\Application\GeoConversion\Decoder;

interface GeoDecoderStrategyInterface
{
    public const SERVICE_TAG = 'geodecoder_strategy';

    public function isDecodable(string $format): bool;
    public function decode($object);
}