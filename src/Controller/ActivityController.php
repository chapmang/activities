<?php

namespace App\Controller;

use App\Entity\Activity;
use App\Repository\ActivityRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
     * @Route("/activity", name="activity_list", methods={"GET"})
     * @param Request $request
     * @param ActivityRepository $repository
     * @param PaginatorInterface $paginator
     * @return Response
     */
    public function index(Request $request, ActivityRepository $repository, PaginatorInterface $paginator)
    {
        $q = $request->query->get('q');
        $queryBuilder = $repository->getWithSearchQueryBuilder($q);

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
     * @Route("/activity/{id}", name="activity_by_id", methods={"GET"}, requirements={"id"="[0-9]*"})
     * @param Activity $activity
     * @return Response
     */
    public function activity(Activity $activity)
    {

        // It's the same as doing find($id) on repository
        switch ($activity->getActivityType()) {
            case "walk":
                return $this->render('walks/view.html.twig',[
                    'activity' => $activity
                ]);
        }



        //return new Response(var_dump($activity));
    }

    /**
     * @Route("/activity/slugged/{slug}", name="activity_by_slug", methods={"GET"}, requirements={"slug"="[a-zA-Z1-9\-_\/]+"})
     * @param Activity $activity
     * @return Response
     */
    public function activityBySlug(Activity $activity)
    {
        // It's the same as doing findOneBy(['slug' => contents of {slug}]) on repository
        return $this->render('base.html.twig',[
            'activity' => $activity
        ]);
        //return new Response('Activity' . $activity);
    }

    /**
     * @Route("/{page}", name="list", methods={"GET"}, requirements={"page"="[0-9]*"})
     * @Route("/page/{page}", name="index_paginated")
     * @param int $page
     * @param Request $request
     * @return Response
     */
    public function list($page = 1, Request $request)
    {
        $limit = $request->get('limit', 20);
        return new Response($request);
    }

    /**
     * @Route("/open", name="open")
     * @param ActivityRepository $repository
     * @return Response
     */
    public function open(ActivityRepository $repository) {

        $activities = $repository->findAllOpenOrderedByRecentUpdate();
        return $this->render('activities/homepage.html.twig', [
            'activities' => $activities
        ]);
    }

    /**
     * @Route("/api/activity/{id}", name="api_activity", methods={"GET"})
     */
    public function apiFetch(){


    }

}