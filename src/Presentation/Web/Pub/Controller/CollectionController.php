<?php
declare(strict_types=1);
namespace App\Presentation\Web\Pub\Controller;

use App\Application\Export\Export;
use App\Domain\Entity\Collection;
use App\Domain\Services\CollectionServices;
use App\Presentation\Web\Pub\Form\ActivityCollectionFormType;
use App\Domain\Repository\CollectionRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\HeaderUtils;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CollectionController extends AbstractController
{

    private $collectionService;

    private $repository;
    /**
     * @var Export
     */
    private $exporter;

    public function __construct(CollectionRepository $collectionRepository,
                                CollectionServices $collectionServices,
                                Export $exporter)
    {
        $this->repository = $collectionRepository;
        $this->collectionService = $collectionServices;
        $this->exporter = $exporter;
    }

    /**
     * @Route("/collection", name="collection_list", methods={"GET"})
     * @param Request $request
     * @param PaginatorInterface $paginator
     * @return Response
     */
    public function index(Request $request, PaginatorInterface $paginator)
    {
        $searchTerm = $request->get('q');

        $collections = $this->repository->findAllWithSearch($searchTerm);

        return $this->render('@Pub/collection/list.html.twig', [
            'collections' => $collections
        ]);
    }

    /**
     * @Route("/collection/new", name="collection_create")
     * @param Request $request
     * @return Response
     */
    public function createCollection(Request $request)
    {
        $collection = new Collection();

        $form = $this->createForm(ActivityCollectionFormType::class, $collection);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->collectionService->createCollection($collection);

            $this->addFlash('success', 'Collection '.$collection->getName().' Created');
            return $this->redirectToRoute('collection_update', ['id' => $collection->getId()]);
        }

        return $this->render('@Pub/collection/new.html.twig', [
            'collectionForm' =>$form->createView()
        ]);
    }

    /**
     * @Route("/collection/update/{id}", name="collection_update", requirements={"id"="[0-9]*"})
     * @param Request $request
     * @return Response
     */
    public function updateCollection(Request $request)
    {
        $collection_id = (int) $request->get('id');
        $collection = $this->collectionService->getCollection($collection_id);

        $form = $this->createForm(ActivityCollectionFormType::class, $collection);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->collectionService->updateCollection($collection);

            $this->addFlash('success', 'Collection '.$collection->getName(). ' Updated');
            return $this->redirectToRoute('collection_update', ['id' => $collection->getId()]);
        }

        return $this->render('@Pub/collection/edit.html.twig', [
            'collection' => $collection,
            'collectionForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/collection/export/{id}", name="collection_export", requirements={"id"="^[0-9]+$"})
     * @param Request $request
     * @return string
     */
    public function exportCollection(Request $request)
    {
       $collection_id = (int) $request->get('id');
       $format = $request->get('format');
       $incGeometry = $request->get('route');

       $collection = $this->collectionService->getCollection($collection_id);

       $download = $this->exporter->exportCollection($format, $collection, $incGeometry, 'details');

       $disposition = HeaderUtils::makeDisposition(
            HeaderUtils::DISPOSITION_ATTACHMENT,
            $collection_id.'-'.$collection->getName().'.'.$format
       );

       $download->headers->set('Content-Disposition', $disposition);
        return $download;
    }

    /**
     * @Route("/collection/api/collections", name="collection_api", methods={"GET"})
     * @param Request $request
     * @return JsonResponse
     */
    public function getCollectionsApi(Request $request)
    {
        $searchTerm = $request->get('q');

        $collections = $this->repository->findAllWithSearch($searchTerm);

        return $this->json([
            'collections' => $collections
        ], 200, [], ['groups' => ['details']]);
    }

    /**
     * @Route("/collection/api/select-activity", name="collection_api_select_activity", methods={"GET"})
     * @param Request $request
     * @return JsonResponse
     */
    public function getActivityByApi(Request $request)
    {
        $searchTerm = $request->query->get('q');
        $searchCollection = $request->query->get('collection');

        $collections = $this->collectionService->getActivitiesNotInCollection($searchCollection, $searchTerm);

        return $this->json([
            'activities' => $collections
        ], 200, [], ['groups' => ['activity']]);
    }


    /**
     * @Route("/collection/{id}", name="collection_by_id", methods={"GET"}, requirements={"id"="[0-9]*"})
     * @param Request $request
     * @return Response
     */
    public function showCollection(Request $request)
    {
        $collection_id = (int) $request->get('id');
        $collection = $this->collectionService->getCollection($collection_id);

        return $this->render('@Pub/collection/view.html.twig', [
            'collection' => $collection
        ]);
    }

    /**
     * @Route("/collection/{slug}", name="collection_by_slug", methods={"GET"}, requirements={"slug"="[a-zA-Z0-9\-_\/]+"})
     * @param Request $request
     * @return Response
     */
    public function showWalkBySlug(Request $request)
    {
        $slug = $request->get('slug');
        $collection = $this->collectionService->getCollection($slug);

        if (!$collection) {
            throw $this->createNotFoundException('collection not found');
        }

        return $this->render('@Pub/collection/view.html.twig', [
            'collection' => $collection
        ]);
    }


}