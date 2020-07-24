<?php
declare(strict_types=1);
namespace App\Application\Export;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Class XmlExportStrategy
 * @package App\Application\Export
 */
final class XmlExportStrategy implements ExportStrategyInterface
{
    private $key = 'xml';

    private $serializer;

    /**
     * XmlExportStrategy constructor.
     * @param SerializerInterface $serializer
     */
    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * @param string $format
     * @return bool
     */
    public function isExportable(string $format): bool
    {
        return $format === $this->key;
    }

    /**
     * @param $activity
     * @param $group
     * @return String
     */
    public function export($activity, $group): string
    {
        $xml = $this->serializer->serialize($activity, $this->key, ['groups' => $group, 'xml_root_node_name' => 'activity']);
        return $xml;
    }
}