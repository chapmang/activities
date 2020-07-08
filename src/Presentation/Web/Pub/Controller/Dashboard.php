<?php
declare(strict_types=1);
namespace App\Presentation\Web\Pub\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class Dashboard extends AbstractController
{

    /**
     * @Route ("/dashboard", name="dashboard")
     * @return Response
     */
    public function dashboard() {

        return $this->render('@Pub/dashboard.html.twig', [
            'walks' => ['count' => 100],
            'drives' => ['count' => 100],
            'rides' => ['count' => 100],
            'pois' => ['count' => 100],
            'collections' => ['count' => 50],

        ]);
    }

}