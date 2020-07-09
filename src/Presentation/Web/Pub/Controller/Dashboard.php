<?php
declare(strict_types=1);
namespace App\Presentation\Web\Pub\Controller;

use App\Domain\Services\DashboardServices;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class Dashboard extends AbstractController
{

    /**
     * @var DashboardServices
     */
    private $dashboardServices;

    public function __construct(DashboardServices $dashboardServices)
    {
        $this->dashboardServices = $dashboardServices;
    }

    /**
     * @Route ("/dashboard", name="dashboard")
     * @return Response
     */
    public function dashboard()
    {

        $counts = $this->dashboardServices->countStats();
        $recent = $this->dashboardServices->recentActivitiesByCategory();


        return $this->render('@Pub/dashboard.html.twig', [
            'walks' => ['count' => $counts['walkCount'], 'recent' => $recent['recentWalks']],
            'drives' => ['count' => $counts['driveCount'], 'recent' => $recent['recentDrives']],
            'rides' => ['count' => $counts['rideCount'], 'recent' => $recent['recentRides']],
            'pois' => ['count' => $counts['poiCount'], 'recent' => $recent['recentPois']],
            'collections' => ['count' => 50, 'recent' => ''],

        ]);
    }

}