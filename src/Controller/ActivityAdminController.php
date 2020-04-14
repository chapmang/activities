<?php

namespace App\Controller;

use App\Entity\Activity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ActivityAdminController extends AbstractController
{


    /**
     * @Route("/activity/{id}/edit", name="activity_admin_edit")
     * @param Activity $activity
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function edit(Activity $activity)
    {
        // Check if unlocked or owned
        $this->denyAccessUnlessGranted('LOCKED', $activity);

        return $this->render('activity_admin/index.html.twig', [
            'controller_name' => 'ActivityAdminController',
        ]);
    }
}
