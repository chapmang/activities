<?php

namespace App\Controller;

use App\Entity\Activity;
use App\Repository\ActivityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ActivityController
 * @package App\Controller
 * @Route("/activity", name="activity_")
 */
class ActivityController extends AbstractController
{
    /**
     * @Route("/{page}", name="activity_list", methods={"GET"}, requirements={"page"="[0-9]*"})
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
     * @Route("/open", name="activity_open")
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
     * @Route("/test/{id}", name="activty_by_id", methods={"GET"}, requirements={"id"="[0-9]*"})
     * @param Activity $activity
     * @return Response
     */
    public function activity(Activity $activity)
    {
        // It's the same as doing find($id) on repository
        return $this->render('base.html.twig',[
            'activity' => $activity
        ]);
       // return new Response(var_dump($activity));
    }

    /**
     * @Route("/{slug}", name="activity_by_slug", methods={"GET"}, requirements={"slug"="[a-zA-Z_][a-zA-Z0-9_]*"})
     * @param Activity $activity
     * @return Response
     */
    public function activityBySlug(Activity $activity)
    {
        // It's the same as doing findOneBy(['slug' => contents of {slug}]) on repository
        return new Response($activity);
        //return new Response('Activity' . $activity);
    }
}