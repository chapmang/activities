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

        $total = $walkCount+$driveCount+$poiCount+$rideCount;

        return array(
            'total' => $total,
            'walkCount' => $walkCount,
            'driveCount' => $driveCount,
            'poiCount' => $poiCount,
            'rideCount' => $rideCount);
    }

    public function recentActivitiesByCategory(int $max)
    {
        $recentWalks = $this->walkRepository->recentModifiedWalk($max);
        $recentDrives = $this->driveRepository->recentModifiedDrive($max);
        $recentPois = $this->poiRepository->recentModifiedPoi($max);
        $recentRides = $this->rideRepository->recentModifiedRide($max);

        return array('walks' => $recentWalks,
            'drives' => $recentDrives,
            'pois' => $recentPois,
            'rides' => $recentRides);
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