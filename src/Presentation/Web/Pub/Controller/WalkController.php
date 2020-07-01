<?php
declare(strict_types=1);
namespace App\Presentation\Web\Pub\Controller;

use App\Application\DownloadManager;
use App\Application\GeoConversion\GeoConverter;
use App\Domain\Entity\Walk;
use App\Domain\Repository\WalkRepository;
use App\Domain\Services\GeographyFactory;
use App\Domain\Services\WalkServices;
use App\Presentation\Web\Pub\Form\WalkFormType;
use App\Application\Export\Export;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\HeaderUtils;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class WalkController
 * @package App\Controller
 */
class WalkController extends AbstractController
{

    /**
     * @var Export
     */
    private $exporter;

    /**
     * @var WalkRepository
     */
    private $repository;

    /**
     * @var WalkServices
     */
    private $walkService;

    /**
     * @var GeoConverter
     */
    private $geoConverter;
    /**
     * @var GeographyFactory
     */
    private $geographyFactory;
    /**
     * @var DownloadManager
     */
    private $downloadManager;

    public function __construct(WalkRepository $repository,
                                WalkServices $walkServices,
                                Export $exporter,
                                GeoConverter $geoconverter,
                                GeographyFactory $geographyFactory,
                                DownloadManager $downloadManager)
    {
        $this->repository = $repository;
        $this->walkService = $walkServices;
        $this->exporter = $exporter;
        $this->geoConverter = $geoconverter;
        $this->geographyFactory = $geographyFactory;
        $this->downloadManager = $downloadManager;
    }

    /**
     * @Route("/walk/new", name="walk_create")
     * @param Request $request
     * @return Response
     */
    public function createWalk(Request $request)
    {
        $walk = new Walk();

        $form = $this->createForm(WalkFormType::class, $walk);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            // WalkService persists the walks and flushes the EntityManager.
            $this->walkService->createWalk($walk);

            $this->addFlash('success', 'Walk ' .$walk->getName(). ' Created');

            return $this->redirectToRoute('walk_update', ['id' => $walk->getId()]);

        }
        return $this->render('@Pub/walk/new.html.twig', [
            'walkForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/walk/update/{id}", name="walk_update", requirements={"id"="^[0-9]+$"})
     * @param Request $request
     * @return Response
     */
    public function updateWalk(Request $request)
    {
        $walk_id = (int) $request->get('id');
        $walk = $this->walkService->getWalk($walk_id);

        // Uses custom voter to check if it the users own account
        // or SysAdmin to allow access
        $this->denyAccessUnlessGranted('LOCKED', $walk);

        $form = $this->createForm(WalkFormType::class, $walk);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            // WalkService updates instance of an Walk,
            // persists it and flushes the EntityManager.
            $this->walkService->updateWalk($walk);

            $this->addFlash('success', 'Walk ' .$walk->getName(). ' Updated ');

            return $this->redirectToRoute('walk_update', ['id' => $walk->getId()]);

        }

        return $this->render('@Pub/walk/edit.html.twig', [
            'activity' => $walk,
            'walkForm' => $form->createView()
        ]);
    }


    /**
     * @Route("/walk/route/{id}", name="walk_route", methods={"POST"}, requirements={"id"="^[0-9]+$"})
     * @param Request $request
     */
    public function updateRoute(Request $request)
    {
        $walk_id = (int) $request->get('id');
        $drawnGeometry = $request->get('route');

        $walk = $this->walkService->getWalk($walk_id);

        $walkRouteText = $this->geoConverter->geojson_to_wkt($drawnGeometry);

        $walkRoute = $this->geographyFactory->createGeographyLineString($walkRouteText->components);
        $walk->setRoute($walkRoute);
        dd($walk);
    }



    /**
     * @Route("/walk/export/{id}", name="walk_export", requirements={"id"="^[0-9]+$"})
     * @param Request $request
     * @return string
     */
    public function exportWalk(Request $request)
    {
        $walk_id = (int) $request->get('id');
        $text_format = $request->get('text_format');
        $route_format = $request->get('route_format');

        $walk = $this->walkService->getWalk($walk_id);


        dd($this->downloadManager->downloadActivity($walk, $text_format, $route_format));
        return $this->downloadManager->downloadActivity($walk, $text_format, $route_format);



        //dd($walk);
        //$test = $this->geoConverter->geojson_to_wkt('{"type":"LineString","coordinates":[[-1.058944,51.281472],[-1.058944,51.281472],[-1.058944,51.281472]]}');
        $this->geoConverter->geojson_to_wkt("<gpx version=\"1.1\" creator=\"Activities-AA Media\" xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" xsi:schemaLocation=\"http://www.topografix.com/GPX/1/1 http://www.topografix.com/GPX/1/1/gpx.xsd\"><metadata><name>rte</name></metadata><rte><name>rte</name><rtept lon=\"-1.058944\" lat=\"51.281472\"></rtept><rtept lon=\"-1.058944\" lat=\"51.281472\"></rtept><rtept lon=\"-1.058944\" lat=\"51.281472\"></rtept></rte> </gpx> ");


        $walk->setRoute();

        $download = $this->exporter->exportActivity($format, $walk, 'activity');

        $disposition = HeaderUtils::makeDisposition(
            HeaderUtils::DISPOSITION_ATTACHMENT,
            $walk_id.'-'.$walk->getName().'.'.$format
        );

        $download->headers->set('Content-Disposition', $disposition);
        return $download;
    }

    /**
     * @Route("/walk/{id}", name="walk_by_id", methods={"GET"}, requirements={"id"="^[0-9]+$"})
     * @param Request $request
     * @return Response
     */
    public function showWalk(Request $request)
    {
        $walk_id = (int) $request->get('id');
        $walk = $this->walkService->getWalk($walk_id);

        if (!$walk) {
            throw $this->createNotFoundException('walk not found');
        }

        return $this->render('@Pub/walk/view.html.twig', [
            'activity' => $walk
        ]);
    }

    /**
     * @Route("/walk/{slug}", name="walk_by_slug", methods={"GET"}, requirements={"slug"="[a-zA-Z0-9\-_\/]+"})
     * @param Request $request
     * @return Response
     */
    public function showWalkBySlug(Request $request)
    {
        $slug = $request->get('slug');
        $walk = $this->walkService->getWalk($slug);

        if (!$walk) {
            throw $this->createNotFoundException('walk not found');
        }

        return $this->render('@Pub/walk/view.html.twig', [
            'activity' => $walk
        ]);
    }

}