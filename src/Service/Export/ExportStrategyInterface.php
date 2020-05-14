<?php


namespace App\Service\Export;


interface ExportStrategyInterface
{
    public const SERVICE_TAG = 'export_strategy';

    public function isDownloadable(string $format): bool;
    public function download($activity, $group);
}