<?php


namespace App\Service\Export;


class ActivityExport
{
    private $strategies;

    public function addStrategy(ExportStrategyInterface $strategy): void
    {
        $this->strategies[] = $strategy;
    }

    public function download(string $format, $activity, $group)
    {

        foreach ($this->strategies as $strategy) {
            if ($strategy->isDownloadable($format)) {
                return $strategy->download($activity, $group);
            }
        }
    }
}