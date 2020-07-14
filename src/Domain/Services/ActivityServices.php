<?php
declare(strict_types=1);
namespace App\Domain\Services;


use App\Application\GeoConversion\GeoConverter;
use App\Domain\Entity\Activity;
use App\Domain\Entity\Walk;
use App\Domain\Repository\ActivityRepositoryInterface;
use App\Service\ActivityStatusUpdater;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

class ActivityServices
{

    /**
     * @var ActivityRepositoryInterface
     */
    private $activityRepository;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var PaginatorInterface
     */
    private $paginator;
    /**
     * @var GeoConverter
     */
    private $geoConverter;

    /**
     * ActivityServices constructor.
     * @param ActivityRepositoryInterface $activityRepository
     * @param EntityManagerInterface $entityManager
     * @param PaginatorInterface $paginator
     */
    public function __construct(ActivityRepositoryInterface $activityRepository,
                                EntityManagerInterface $entityManager,
                                PaginatorInterface $paginator,
                                GeoConverter $geoconverter)
    {
        $this->activityRepository = $activityRepository;
        $this->entityManager = $entityManager;
        $this->paginator = $paginator;
        $this->geoConverter = $geoconverter;

    }


    /**
     * @param $term
     * @param $pageNumber
     * @return PaginationInterface
     */
    public function getPaginatedSearchResults($term, $pageNumber)
    {
        $queryBuilder = $this->activityRepository->getWithSearchQueryBuilder($term);

        $pagination = $this->paginator->paginate(
            $queryBuilder, /* query NOT result */
            $pageNumber/*page number*/,
            10/*limit per page*/
        );

        return $pagination;
    }

    /**
     * @param $activity_id
     * @param $status
     * @return Activity
     */
    public function updateActivityStatus($activity_id, $status): Activity
    {
        $activity = $this->activityRepository->findById($activity_id);

        // The aim is to change the status so set value to opposite of submitted
        switch ($status){
            case "0":
                $activity->setStatus(1);
                break;
            case "1":
                $activity->setStatus(0);
                break;
        }

        $this->activityRepository->add($activity);

        $this->entityManager->flush();

        return $activity;

    }

    /**
     * @param $id
     * @return Activity|null
     */
    public function getActivity($id): ?Activity
    {
        return $this->activityRepository->findById($id);

    }

    public function getMapActivities()
    {
        $activityArray['features'] = [];
        $activities = $this->activityRepository->findAllMapActivities();
        $i = 0;
        foreach ($activities as $activity)
        {
            foreach ($activity as $key => $value)
            {
                $activityArray['features'][$i]['type'] = 'Feature';
                if ($key == 'point') {
                    $b = $this->geoConverter->wkt_to_json($value);
                    $activityArray['features'][$i]['geometry'] = json_decode($b);
                } else {
                    $activityArray['features'][$i]['properties'][$key] = $value;
                }
            }
            $i++;
        }
        return $activityArray;
    }


}