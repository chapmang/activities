<?php

namespace App\Service\Export;

use Symfony\Component\Serializer\SerializerInterface;

class JsonExportStrategy implements ExportStrategyInterface
{
    private $key = 'json';

    private $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    public function isDownloadable(string $format): bool
    {
        return $format === $this->key;
    }

    public function download($activity, $group)
    {
        return $this->serializer->serialize($activity, $this->key, ['groups' => $group]);
    }
}