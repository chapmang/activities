<?php
declare(strict_types=1);
namespace App\Application\Export;

use App\Domain\Entity\Activity;

/**
 * Class ActivityExport
 * @package App\Application\Export
 */
class Export
{
    private $strategies;

    /**
     * @param ExportStrategyInterface $strategy
     */
    public function addStrategy(ExportStrategyInterface $strategy): void
    {
        $this->strategies[] = $strategy;
    }

    /**
     * @param string $format
     * @param $activity
     * @param $group
     * @return mixed
     */
    public function exportActivity(Activity $activity, string $format,  $group)
    {

        foreach ($this->strategies as $strategy) {
            if ($strategy->isExportable($format)) {
                return $strategy->export($activity, $group);
            }
        }
    }

    /**
     * @param string $format
     * @param $collection
     * @param bool $geometry
     * @param null $group
     * @return mixed
     */
    public function exportCollection(string $format, $collection, $geometry = false, $group = null)
    {
        foreach ($this->strategies as $strategy) {
            if ($strategy->isExportable($format)) {
                return $strategy->export($collection, $group);
            }
        }
    }
}