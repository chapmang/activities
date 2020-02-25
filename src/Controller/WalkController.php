<?php


namespace App\Controller;


use App\Entity\User;
use App\Entity\Walk;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class WalkController
 * @package App\Controller
 * @Route("/walk", name="walk_")
 */
class WalkController extends AbstractController
{
    /**
     * @Route("/new")
     * @param EntityManagerInterface $em
     * @return Response
     */
    public function new(EntityManagerInterface $em) {

        return new Response('Lets Go Walking');
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