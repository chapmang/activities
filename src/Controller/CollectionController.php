<?php


namespace App\Controller;

use App\Entity\Collection;
use App\Form\ActivityCollectionFormType;
use App\Repository\ActivityRepository;
use App\Repository\CollectionContentsRepository;
use App\Repository\CollectionRepository;
use App\Service\CollectionFacade;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CollectionController extends AbstractController
{

    private $collectionFacade;

    private $repository;

    public function __construct(CollectionRepository $collectionRepository, CollectionFacade $collectionFacade)
    {
        $this->repository = $collectionRepository;
        $this->collectionFacade = $collectionFacade;
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

        return $this->render('collection/list.html.twig', [
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

            $this->collectionFacade->createCollection($collection);

            $this->addFlash('success', 'Collection '.$collection->getName().' Created');
            return $this->redirectToRoute('collection_update', ['id' => $collection->getId()]);
        }

        return $this->render('collection/new.html.twig', [
            'collectionForm' =>$form->createView()
        ]);
    }

    /**
     * @Route("/collection/update/{id}", name="collection_update", requirements={"id"="[0-9]*"})
     * @param Collection $collection
     * @param Request $request
     * @return Response
     */
    public function updateCollection(Collection $collection, Request $request)
    {
        $form = $this->createForm(ActivityCollectionFormType::class, $collection);
        $form->handleRequest($request);
        //dd($collection);
        if ($form->isSubmitted() && $form->isValid()) {

            $this->collectionFacade->updateCollection($collection);

            $this->addFlash('success', 'Collection '.$collection->getName(). ' Updated');
            return $this->redirectToRoute('collection_update', ['id' => $collection->getId()]);
        }

        return $this->render('collection/edit.html.twig', [
            'collection' => $collection,
            'collectionForm' => $form->createView()
        ]);
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

        $collections = $this->repository->searchActivitiesNotInCollection($searchCollection, $searchTerm);

        return $this->json([
            'activities' => $collections
        ], 200, [], ['groups' => ['activity']]);
    }

    public function exportCollection(Collection $collection, $format)
    {

    }

    /**
     * @Route("/collection/{id}", name="collection_by_id", methods={"GET"}, requirements={"id"="[0-9]*"})
     * @param Collection $collection
     * @return Response
     */
    public function showCollection(Collection $collection)
    {
        return $this->render('collection/view.html.twig', [
            'collection' => $collection
        ]);
    }

    /**
     * @Route("/collection/{slug}", name="collection_by_slug", methods={"GET"}, requirements={"slug"="[a-zA-Z0-9\-_\/]+"})
     * @param Collection $collection
     * @return Response
     */
    public function showWalkBySlug(Collection $collection)
    {
        return $this->render('collection/view.html.twig', [
            'collection' => $collection
        ]);
    }


}