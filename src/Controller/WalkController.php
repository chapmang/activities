<?php


namespace App\Controller;


use App\Entity\User;
use App\Entity\Walk;
use App\Form\WalkFormType;
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
    /**
     * @Route("/walk/new", name="app_walk_createwalk")
     * @param EntityManagerInterface $em
     * @param Request $request
     * @return Response
     */
    public function createWalk(EntityManagerInterface $em, Request $request)
    {
        $form = $this->createForm(WalkFormType::class);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            /** @var Walk $walk */
            $walk = $form->getData();
            $em->persist($walk);
            $em->flush();

            $this->addFlash('success', 'Walk ' .$walk->getName(). ' Created ');

            return $this->redirectToRoute('app_walk_edit', ['id' => $walk->getId()]);

        }
        return $this->render('walk/new.html.twig', [
            'walkForm' => $form->createView()
        ]);
    }

    /**
     * @Route("walk/edit/{id}", name="app_walk_edit")
     * @param Walk $walk
     * @param EntityManagerInterface $em
     * @param Request $request
     * @return Response
     */
    public function editWalk(Walk $walk, EntityManagerInterface $em, Request $request){

        $form = $this->createForm(WalkFormType::class, $walk);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            /** @var Walk $walk */
            $walk = $form->getData();
            $em->persist($walk);
            $em->flush();

            $this->addFlash('success', 'Walk ' .$walk->getName(). ' Updated ');

            return $this->redirectToRoute('app_walk_edit', ['id' => $walk->getId()]);

        }
        return $this->render('walk/edit.html.twig', [
            'walkForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/m/{id}", name="activty_by_id", methods={"GET"}, requirements={"id"="[0-9]*"})
     * @param Walk $walk
     * @return Response
     */
    public function activity(Walk $walk)
    {
        // It's the same as doing find($id) on repository
        return new Response(var_dump($walk));
    }
}