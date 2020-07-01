<?php
declare(strict_types=1);
namespace App\Application\Export;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Class CsvExportStrategy
 * @package App\Application\Export
 */
final class CsvExportStrategy implements ExportStrategyInterface
{
    private $key = 'csv';

    private $serializer;

    /**
     * CsvExportStrategy constructor.
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
        $csv = $this->serializer->serialize($activity, $this->key, ['groups' => $group]);
        return $csv;
    }

}