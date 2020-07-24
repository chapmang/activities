<?php
declare(strict_types=1);
namespace App\Domain\Services;


use App\Application\GeoConversion\GeoConverter;
use App\Domain\Repository\ActivityRepository;
use App\Domain\Repository\CollectionContentsRepository;
use App\Domain\Repository\CollectionRepository;

final class MapServices
{

    /**
     * @var CollectionContentsRepository
     */
    private $collectionContentsRepository;
    /**
     * @var ActivityRepository
     */
    private $activityRepository;
    /**
     * @var GeoConverter
     */
    private $geoConverter;
    /**
     * @var CollectionRepository
     */
    private $collectionRepository;


    /**
     * MapServices constructor.
     * @param CollectionContentsRepository $collectionContentsRepository
     * @param ActivityRepository $activityRepository
     * @param GeoConverter $geoconverter
     */
    public function __construct(CollectionContentsRepository $collectionContentsRepository,
                                CollectionRepository $collectionRepository,
                                ActivityRepository $activityRepository,
                                GeoConverter $geoconverter)
    {
        $this->collectionContentsRepository = $collectionContentsRepository;
        $this->collectionRepository = $collectionRepository;
        $this->activityRepository = $activityRepository;
        $this->geoConverter = $geoconverter;
    }

    public function getAllActivities(): array
    {
        $activities = $this->activityRepository->findAllMapActivities();
        $formated = $this->formatAsGeoJson($activities);

        return $formated;
    }

    public function getCollectionActivities(int $collection_id): array
    {
       $collectionContents = $this->collectionContentsRepository->findActivitiesByCollection($collection_id);
       $formated = $this->formatAsGeoJson($collectionContents);

       return $formated;
    }

    private function formatAsGeoJson($activities)
    {
        $activityArray = [];
        $i = 0;
        foreach ($activities as $content)
        {
            $activityArray[$i] = json_decode($this->geoConverter->geom_to_geojson($content));
            $i++;
        }
        $f = [];
        $f['type'] = 'FeatureCollection';
        $f['features'] = $activityArray;
        return $f;
    }
}