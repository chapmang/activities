<?php


namespace App\Controller;

use App\Entity\Walk;
use App\Form\WalkFormType;
use App\Service\Export\ActivityExport;
use App\Service\WalkFacade;
use App\Model\ModelFactory\WalkModelFactory;
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
    private $walkFacade;

    private $export;

    public function __construct(WalkFacade $walkFacade, ActivityExport $export)
    {
        $this->walkFacade = $walkFacade;
        $this->export = $export;
    }

    /**
     * @Route("/walk/new", name="walk_create")
     * @param Request $request
     * @return Response
     */
    public function createWalk(Request $request)
    {
        $walk = new Walk();
        $walkModel = WalkModelFactory::createActivity();
        $form = $this->createForm(WalkFormType::class, $walk);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            // WalkFacade persists the walks and flushes the EntityManager.
            $this->walkFacade->createWalk($walk);

            $this->addFlash('success', 'Walk ' .$walk->getName(). ' Created');

            return $this->redirectToRoute('walk_update', ['id' => $walk->getId()]);

        }
        return $this->render('walk/new.html.twig', [
            'walkForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/walk/update/{id}", name="walk_update", requirements={"id"="[0-9]*"})
     * @param Walk $walk
     * @param Request $request
     * @return Response
     */
    public function updateWalk(Walk $walk, Request $request)
    {
        // Uses custom voter to check if it the users own account
        // or SysAdmin to allow access
        $this->denyAccessUnlessGranted('LOCKED', $walk);

        $walkModel = WalkModelFactory::createActivity($walk);
        $form = $this->createForm(WalkFormType::class, $walk);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            // WalkFacade updates instance of an Walk,
            // persists it and flushes the EntityManager.
            $this->walkFacade->updateWalk($walk);

            $this->addFlash('success', 'Walk ' .$walk->getName(). ' Updated ');

            return $this->redirectToRoute('walk_update', ['id' => $walk->getId()]);

        }

        return $this->render('walk/edit.html.twig', [
            'activity' => $walk,
            'walkForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/walk/export/{format}/{id}", name="walk_export", requirements={"id"="[0-9]*"})
     * @param Walk $walk
     * @param $format
     * @return string
     */
    public function exportWalk(Walk $walk, $format)
    {
        $walkModel = WalkModelFactory::createActivity($walk);
        // @TODO Make sure Models (DTOs) support serializer groups

        $download = $this->export->download($format, $walk, 'activity');

        $disposition = HeaderUtils::makeDisposition(
            HeaderUtils::DISPOSITION_ATTACHMENT,
            $walk->getId().'-'.$walk->getName().'.'.$format
        );
        $download->headers->set('Content-Disposition', $disposition);
        return $download;
    }

    /**
     * @Route("/walk/{id}", name="walk_by_id", methods={"GET"}, requirements={"id"="[0-9]*"})
     * @param Walk $walk
     * @return Response
     */
    public function showWalk(Walk $walk)
    {
        return $this->render('walk/view.html.twig', [
            'activity' => $walk
        ]);
    }

    /**
     * @Route("/walk/{slug}", name="walk_by_slug", methods={"GET"}, requirements={"slug"="[a-zA-Z0-9\-_\/]+"})
     * @param Walk $walk
     * @return Response
     */
    public function showWalkBySlug(Walk $walk)
    {
        return $this->render('walk/view.html.twig', [
            'activity' => $walk
        ]);
    }


}