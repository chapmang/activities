<?php
declare(strict_types=1);
namespace App\Presentation\Web\Pub\Controller;

use App\Domain\Services\CollectionServices;
use App\Domain\Services\DashboardServices;
use App\Domain\Services\DriveServices;
use App\Domain\Services\PoiServices;
use App\Domain\Services\RideServices;
use App\Domain\Services\WalkServices;
use App\Presentation\Web\Pub\Form\sideSearchFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{

    /**
     * @var DashboardServices
     */
    private $dashboardServices;
    /**
     * @var WalkServices
     */
    private $walkService;
    /**
     * @var RideServices
     */
    private $rideService;
    /**
     * @var DriveServices
     */
    private $driveService;
    /**
     * @var PoiServices
     */
    private $poiService;
    /**
     * @var CollectionServices
     */
    private $collectionService;

    public function __construct(DashboardServices $dashboardServices,
                                WalkServices $walkServices,
                                RideServices $rideServices,
                                DriveServices $driveServices,
                                PoiServices $poiServices,
                                CollectionServices $collectionServices)
    {
        $this->dashboardServices = $dashboardServices;
        $this->walkService = $walkServices;
        $this->rideService = $rideServices;
        $this->driveService = $driveServices;
        $this->poiService = $poiServices;
        $this->collectionService = $collectionServices;
    }

    /**
     * @Route ("/dashboard", name="dashboard")
     * @param Request $request
     * @return Response
     */
    public function dashboard(Request $request)
    {
        $walksCount = $this->walkService->countWalks();
        $ridesCount = $this->rideService->countRides();
        $drivesCount = $this->driveService->countDrives();
        $poisCount = $this->poiService->countPois();
        // $collectionsCount = $this->collectionService->countCollections();

        $recentWalks = $this->walkService->recentModifiedWalk(5);
        $recentRides = $this->rideService->recentModifiedRide(5);
        $recentDrives = $this->driveService->recentModifiedDrive(5);
        $recentPois = $this->poiService->recentModifiedPoi(5);
        // $recentCollections = $this->collectionService->recentModifiedCollection(5);

        return $this->render('@Pub/dashboard/dashboard.html.twig', [
            'walks' => ['count' => $walksCount, 'recentWalks' => $recentWalks],
            'rides' => ['count' => $ridesCount, 'recentRides' => $recentRides],
            'drives' => ['count' => $drivesCount, 'recentDrives' => $recentDrives],
            'pois' => ['count' => $poisCount, 'recentPois' => $recentPois],
            'collections' => ['count' => 50, 'recent' => ''],

        ]);
    }

    /**
     * @Route ("/dashboard/search", name="dashboard_search")
     * @param Request $request
     * @return Response
     */
    public function search(Request $request)
    {
        $form = $this->createForm(sideSearchFormType::class, null, [
            'action' => $this->generateUrl('dashboard_search')
        ]);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $queryResults = $this->dashboardServices->globalSearch($form->getData());

            return $this->render('@Pub/dashboard/searchResults.html.twig', [
                'searchQuery' => $form->getData()['string'],
                'searchResults' => $queryResults,
                'dashboardSearchForm' => $form->createView()
            ]);
        }

        return $this->render('@Pub/dashboard/_searchForm.hml.twig', [
            'dashboardSearchForm' => $form->createView()
        ]);
    }
}