<?php
declare(strict_types=1);
namespace App\Presentation\Web\Pub\Controller;

use App\Domain\Services\ActivityServices;
use App\Domain\Services\CollectionServices;
use App\Domain\Services\MapServices;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class MapController
 * @package App\Presentation\Web\Pub\Controller
 */
class MapController extends AbstractController
{

    /**
     * @var MapServices
     */
    private $mapService;

    public function __construct(MapServices $mapServices)
    {
        $this->mapService = $mapServices;
    }

    /**
     * @Route("/map", name="map")
     */
    public function map()
    {
        return $this->render('@Pub/map/map.html.twig');
    }

    /**
     * @Route("/map/activities/{collection}", name="collection_activities")
     * @param Request $request
     * @return JsonResponse
     */
    public function collectionActivities(Request $request)
    {
        $collection_id = (int)$request->get('collection');
        $activities = $this->mapService->getCollectionActivities($collection_id);
        return new JsonResponse($activities);
    }

    /**
     * @Route("/map/activities", name="map_activities")
     * @return JsonResponse
     */
    public function allActivities()
    {
        $activities = $this->mapService->getAllActivities();
        return new JsonResponse($activities);
    }
}