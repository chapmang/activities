<?php
declare(strict_types=1);
namespace App\Application\Export;

use Symfony\Component\HttpFoundation\Response;

/**
 * Interface ExportStrategyInterface
 * @package App\Application\Export
 */
interface ExportStrategyInterface
{
    public const SERVICE_TAG = 'export_strategy';

    public function isExportable(string $format): bool;
    public function export($activity, $group);
}