<?php


namespace App\Controller;


use App\Entity\Activity;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ActivityController
{
    /**
     * @Route("/")
     * @return Response
     */
    public function homepage()
    {
        return new Response('Hello');
    }

    /**
     * @Route("/activity/{id}", name="activty_by_id", methods={"GET"}, requirements={"id"="\d+"})
     * @param Activity $activity
     * @return Response
     */
    public function activity(Activity $activity)
    {
        return new Response('Activity' . $activity);
    }

    /**
     * @Route("/activity/{slug}", name="activity_by_slug", methods={"GET"})
     * @param Activity $activity
     * @return Response
     */
    public function activityBySlug(Activity $activity)
    {

        return new Response('Activity' . $activity);
    }
}