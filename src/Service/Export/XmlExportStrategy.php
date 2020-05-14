<?php

namespace App\Service\Export;

use Symfony\Component\Serializer\SerializerInterface;

class XmlExportStrategy implements ExportStrategyInterface
{
    private $key = 'xml';

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
        return $this->serializer->serialize($activity, $this->key, ['groups' => $group, 'xml_root_node_name' => 'activity']);
    }
}