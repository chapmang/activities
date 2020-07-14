<?php
declare(strict_types=1);
namespace App\Domain\Services;


use App\Domain\Repository\CollectionRepository;
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
    /**
     * @var CollectionRepository
     */
    private $collectionRepository;

    public function __construct(WalkRepository $walkRepository,
                                DriveRepository $driveRepository,
                                PoiRepository $poiRepository,
                                RideRepository $rideRepository,
                                CollectionRepository $collectionRepository)
    {
        $this->walkRepository = $walkRepository;
        $this->driveRepository = $driveRepository;
        $this->poiRepository = $poiRepository;
        $this->rideRepository = $rideRepository;
        $this->collectionRepository = $collectionRepository;
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

    public function globalSearch(array $queryData)
    {
        $results = [];
        $queryTerm = $queryData['string'];
        $tags = $queryData['tags'];

        $results['walks'] = $this->walkRepository->findAllByNameAndTags($queryTerm, $tags);
        $results['drives'] = $this->driveRepository->findAllByNameAndTags($queryTerm, $tags);
        $results['pois'] = $this->poiRepository->findAllByNameAndTags($queryTerm, $tags);
        $results['rides'] = $this->rideRepository->findAllByNameAndTags($queryTerm, $tags);
        $results['collections'] = $this->collectionRepository->findAllByNameAndTags($queryTerm, $tags);

        return $results;
    }
}