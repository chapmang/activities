<?php


namespace App\Controller;

use App\Repository\TagRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;


class TagController extends AbstractController
{
    /**
     * @Route("/tags.json", name="tags", defaults={"_format": "json"})
     * @param TagRepository $repository
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function tagsAction(TagRepository $repository)
    {
        $tags = $repository->findBy([], ['name' => 'ASC']);

        return $this->render('tags/tags.json.twig', ['tags' => $tags]);
    }
}