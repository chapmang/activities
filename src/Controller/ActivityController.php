<?php

namespace App\Controller;

use App\Entity\Activity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
     * @Route("/", name="index")
     * @return Response
     */
    public function index()
    {
        return new Response('Hello');
    }

    /**
     * @Route("/{id}", name="activty_by_id", methods={"GET"}, requirements={"id"="\d+"})
     * @param Activity $activity
     * @return Response
     */
    public function activity(Activity $activity)
    {
        return new Response('Hello');
    }

    /**
     * @Route("/{slug}", name="activity_by_slug", methods={"GET"})
     * @param Activity $activity
     * @return Response
     */
    public function activityBySlug(Activity $activity)
    {

        return new Response('Activity' . $activity);
    }
}