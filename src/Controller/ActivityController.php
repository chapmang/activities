<?php

namespace App\Controller;

use App\Model\ModelFactory\ActivityModelFactory;
use App\Repository\ActivityRepository;
use App\Service\ActivityFacade;
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
    private $activityFacade;

    private $repository;

    public function __construct(ActivityRepository $repository, ActivityFacade $activityFacade)
    {
        $this->activityFacade = $activityFacade;
        $this->repository = $repository;
    }

    /**
     * @Route("/activity", name="activity_list", methods={"GET"})
     * @param Request $request
     * @param PaginatorInterface $paginator
     * @return Response
     */
    public function index(Request $request, PaginatorInterface $paginator)
    {

        $q = $request->query->get('q');
        $queryBuilder = $this->repository->getWithSearchQueryBuilder($q);

        $pagination = $paginator->paginate(
            $queryBuilder, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            10/*limit per page*/
        );

        return $this->render('activities/homepage.html.twig', [
            'pagination' => $pagination
        ]);
    }

    /**
     * @Route("/activity/lock", name="lock_activity", defaults={"_format": "json"})
     * @param Request $request
     * @param ActivityRepository $activityRepository
     * @param ActivityStatusUpdater $activityStatusUpdater
     * @return Response
     */
    public function lockActivity(Request $request, ActivityRepository $activityRepository, ActivityStatusUpdater $activityStatusUpdater)
    {
        $activity = $activityRepository->find($request->query->get('activity'));
        $statusRequest = $request->query->get('status');
        $activityModel = ActivityModelFactory::createActivity($activity);
        $updatedActivityModel = $activityStatusUpdater->updateStatus($activityModel, $statusRequest);

        $this->activityFacade->updateWalk($activity, $updatedActivityModel);
        // @TODO insert try catch and find out how to pass by error via javascript

        return new JsonResponse([
            'status' => $updatedActivityModel->getStatus(),
            'user' => $this->getUser()->getUsername()
        ]);
    }


}