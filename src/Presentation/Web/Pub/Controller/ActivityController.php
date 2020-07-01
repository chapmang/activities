<?php
declare(strict_types=1);
namespace App\Presentation\Web\Pub\Controller;

use App\Domain\Services\ActivityServices;
use App\Domain\Repository\ActivityRepository;
use App\Service\ActivityStatusUpdater;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ActivityController
 * @package App\Controller
 */
class ActivityController extends AbstractController
{
    /**
     * @var ActivityRepository
     */
    private $repository;

    /**
     * @var ActivityServices
     */
    private $activityService;

    public function __construct(ActivityRepository $repository, ActivityServices $activityServices)
    {
        $this->repository = $repository;
        $this->activityService = $activityServices;
    }

    /**
     * @Route("/activity", name="activity_list", methods={"GET"})
     * @param Request $request
     * @param PaginatorInterface $paginator
     * @return Response
     */
    public function index(Request $request, PaginatorInterface $paginator)
    {

        $searchTerm = $request->query->get('q');
        $pageNumber = $request->query->getInt('page', 1);
        $pagination = $this->activityService->getPaginatedSearchResults($searchTerm, $pageNumber);

        return $this->render('@Pub/activities/homepage.html.twig', [
            'pagination' => $pagination
        ]);
    }

    /**
     * @Route("/activity/lock", name="lock_activity", defaults={"_format": "json"})
     * @param Request $request
     * @param ActivityStatusUpdater $activityStatusUpdater
     * @return Response
     */
    public function lockActivity(Request $request, ActivityStatusUpdater $activityStatusUpdater)
    {
        $activity_id = $request->query->getInt('activity');
        $statusRequest = $request->query->get('status');

        $activity = $this->activityService->updateActivityStatus($activity_id, $statusRequest);

        return new JsonResponse([
            'status' => $activity->getStatus(),
            'user' => $this->getUser()->getUsername()
        ]);
    }


}