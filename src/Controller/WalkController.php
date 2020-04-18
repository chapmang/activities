<?php


namespace App\Controller;

use App\Entity\Walk;
use App\Form\WalkFormType;
use App\Service\WalkFacade;
use App\Service\WalkModelFactory;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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

    public function __construct(WalkFacade $walkFacade)
    {
        $this->walkFacade = $walkFacade;
    }

    /**
     * @Route("/walk/new", name="walk_create")
     * @param Request $request
     * @return Response
     */
    public function createWalk( Request $request)
    {
        $walkModel = WalkModelFactory::build();
        $form = $this->createForm(WalkFormType::class, $walkModel);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            // WalkFacade creates instance of an Walk,
            // persists it and flushes the EntityManager.
            $walk = $this->walkFacade->createWalk($walkModel);

            $this->addFlash('success', 'Walk ' .$walk->getName(). ' Created ');

            return $this->redirectToRoute('walk_update', ['id' => $walk->getId()]);

        }
        return $this->render('walk/new.html.twig', [
            'walkForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/walk/update/{id}", name="walk_update", requirements={"id"="[0-9]*"})
     * @param Walk $walk
     * @param EntityManagerInterface $em
     * @param Request $request
     * @return Response
     */
    public function updateWalk(Walk $walk, EntityManagerInterface $em, Request $request){

        $walkModel = WalkModelFactory::build($walk);
        $form = $this->createForm(WalkFormType::class, $walkModel);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            // WalkFacade updates instance of an Walk,
            // persists it and flushes the EntityManager.
            $this->walkFacade->updateWalk($walk, $walkModel);

            $this->addFlash('success', 'Walk ' .$walk->getName(). ' Updated ');

            return $this->redirectToRoute('walk_update', ['id' => $walk->getId()]);

        }
        return $this->render('walk/edit.html.twig', [
            'walkForm' => $form->createView()
        ]);
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