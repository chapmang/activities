<?php


namespace App\Controller;

use App\Entity\Walk;
use App\Form\WalkFormType;
use App\Service\WalkFacade;
use App\Model\ModelFactory\WalkModelFactory;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\HeaderUtils;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

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
        $walkModel = WalkModelFactory::createActivity();
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

        $walkModel = WalkModelFactory::createActivity($walk);
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
            'activity' => $walk,
            'walkForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/walk/export/{format}/{id}", name="walk_export", requirements={"id"="[0-9]*"})
     * @param Walk $walk
     * @param SerializerInterface $serializer
     * @param $format
     * @return string
     */
    public function exportWalk(Walk $walk, SerializerInterface $serializer, $format)
    {
        $serializedActivity = $serializer->serialize($walk, $format, ['groups' => 'activity', 'xml_root_node_name' => 'activity']);
        $response = new Response($serializedActivity);
        $disposition = HeaderUtils::makeDisposition(
            HeaderUtils::DISPOSITION_ATTACHMENT,
            $walk->getId().'-'.$walk->getName().'.'.$format
        );
        $response->headers->set('Content-Disposition', $disposition);
        return $response;
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