<?php
declare(strict_types=1);
namespace App\Presentation\Web\Pub\Controller;

use App\Application\DownloadManager;
use App\Application\GeoConversion\GeoConverter;
use App\Domain\Entity\Walk;
use App\Domain\Repository\WalkRepository;
use App\Domain\Services\WalkServices;
use App\Presentation\Web\Pub\Form\sideSearchFormType;
use App\Presentation\Web\Pub\Form\WalkFormType;
use App\Application\Export\Export;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
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
     * @var WalkServices
     */
    private $walkService;

    /**
     * @var GeoConverter
     */
    private $geoConverter;

    /**
     * @var DownloadManager
     */
    private $downloadManager;

    public function __construct(WalkServices $walkServices,
                                Export $exporter,
                                GeoConverter $geoconverter,
                                DownloadManager $downloadManager)
    {
        $this->walkService = $walkServices;
        $this->exporter = $exporter;
        $this->geoConverter = $geoconverter;
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
        $form->get('json_route')->setData($this->geoConverter->geom_to_geojson($walk->getRoute()));

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $walk->setRoute($this->geoConverter->geojson_to_geom($form['json_route']->getData()));
            //$walk->setPoint($this->geoConverter->geojson_to_geom($form['json_point']->getData()));

            // WalkService updates instance of an Walk,
            // persists it and flushes the EntityManager.
            $this->walkService->updateWalk($walk);

            $this->addFlash('success', 'Walk ' .$walk->getName(). ' Updated ');

            if ($form['save_type']->getData() == 'save-close') {
                return $this->redirectToRoute('walk_by_slug', ['slug' => $walk->getSlug()]);
            }
        }
        return $this->render('@Pub/walk/edit.html.twig', [
            'activity' => $walk,
            'walkForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/walk/export/{id}", name="walk_export", methods={"POST"}, requirements={"id"="^[0-9]+$"})
     * @param Request $request
     * @return string
     */
    public function exportWalk(Request $request)
    {
        $data = json_decode(
            $request->getContent(),
            true
        );
        $walk_id = (int) $request->get('id');
        $text_format = $data['text_format'];
        $route_format = $data['route_format'];

        $walk = $this->walkService->getWalk($walk_id);

        $fileName = $this->downloadManager->downloadActivity($walk, $text_format, $route_format, true);
        $url = $this->generateUrl('zip_route', ['file' => $fileName]);
        return new JsonResponse(['url' => $url]);
    }

    /**
     * @Route ("/zip", name="zip_route", methods={"GET"})
     * @param Request $request
     * @return Response
     */
    public function downloadZip(Request $request)
    {
        $fileName = $request->query->get('file');
        $response = $this->downloadManager->downloadZipFile($fileName);
        return $response;
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
            'activity' => $walk,
            'route' => $this->geoConverter->geom_to_geojson($walk->getRoute())
        ]);
    }

    /**
     * @Route ("/walk", name="walk_list", methods={"GET"})
     * @param Request $request
     * @return Response
     */
    public function search(Request $request)
    {
        $form = $this->createForm(sideSearchFormType::class, null, [
            'action' => $this->generateUrl('walk_list'),
            'method' => 'GET'
        ]);
        $pageNumber = $request->query->getInt('page', 1);
        $form->handleRequest($request);

        $pagination = $this->walkService->getPaginatedSearchResults($form->getData(), $pageNumber);

        return $this->render('@Pub/walk/list.html.twig', [
            'searchQuery' => ($form->getData() ? $form->getData()['string'] : null),
            //'searchResults' => $queryResults,
            'pagination' => $pagination,
            'sideSearchForm' => $form->createView()
        ]);
    }

}