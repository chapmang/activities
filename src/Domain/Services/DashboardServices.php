<?php
declare(strict_types=1);
namespace App\Domain\Services;


use App\Domain\Repository\DriveRepository;
use App\Domain\Repository\PoiRepository;
use App\Domain\Repository\RideRepository;
use App\Domain\Repository\WalkRepository;

class DashboardServices
{
    /**
     * @var WalkRepository
     */
    private $walkRepository;
    /**
     * @var DriveRepository
     */
    private $driveRepository;
    /**
     * @var PoiRepository
     */
    private $poiRepository;
    /**
     * @var RideRepository
     */
    private $rideRepository;

    public function __construct(WalkRepository $walkRepository,
                                DriveRepository $driveRepository,
                                PoiRepository $poiRepository,
                                RideRepository $rideRepository)
    {
        $this->walkRepository = $walkRepository;
        $this->driveRepository = $driveRepository;
        $this->poiRepository = $poiRepository;
        $this->rideRepository = $rideRepository;
    }

    public function countStats()
    {
        $walkCount = $this->walkRepository->size();
        $driveCount = $this->driveRepository->size();
        $poiCount = $this->poiRepository->size();
        $rideCount = $this->rideRepository->size();

        return array('walkCount' => $walkCount,
            'driveCount' => $driveCount,
            'poiCount' => $poiCount,
            'rideCount' => $rideCount);
    }

    public function recentActivitiesByCategory()
    {
        $recentWalks = $this->walkRepository->recentModifiedWalk();
        $recentDrives = $this->driveRepository->recentModifiedDrive();
        $recentPois = $this->poiRepository->recentModifiedPoi();
        $recentRides = $this->rideRepository->recentModifiedRide();

        return array('recentWalks' => $recentWalks,
            'recentDrives' => $recentDrives,
            'recentPois' => $recentPois,
            'recentRides' => $recentRides);
    }
}