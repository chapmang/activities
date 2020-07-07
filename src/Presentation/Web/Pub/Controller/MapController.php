<?php
declare(strict_types=1);
namespace App\Presentation\Web\Pub\Controller;

use App\Domain\Services\ActivityServices;
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
     * @var ActivityServices
     */
    private $activityService;

    public function __construct(ActivityServices $activityServices)
    {
        $this->activityService = $activityServices;
    }

    /**
     * @Route("/map", name="map")
     */
    public function map()
    {
        return $this->render('@Pub/map/map.html.twig');
    }

    /**
     * @Route("/map/activities", name="map_activities")
     */
    public function mapActivities()
    {
        $activities = $this->activityService->getMapActivities();
        return new JsonResponse($activities);
    }
}