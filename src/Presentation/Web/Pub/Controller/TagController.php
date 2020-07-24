<?php


namespace App\Presentation\Web\Pub\Controller;

use App\Presentation\Web\Pub\Form\WalkFormType;
use App\Domain\Repository\TagRepository;
use App\Service\Autocomplete;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class TagController extends AbstractController
{

    /**
     * @var FormFactoryInterface
     */
    private $formFactory;
    /**
     * @var ManagerRegistry
     */
    private $doctrine;
    /**
     * @var Autocomplete
     */
    private $autocomplete;

    public function __construct(FormFactoryInterface $formFactory, ManagerRegistry $doctrine, Autocomplete $autocomplete)
    {
        $this->formFactory = $formFactory;
        $this->doctrine = $doctrine;
        $this->autocomplete = $autocomplete;
    }

    /**
     * @Route("/tags.json", name="tags", defaults={"_format": "json"})
     * @param Request $request
     * @param TagRepository $repository
     * @return Response
     */
    public function tagsAction(Request $request, TagRepository $repository)
    {

        $tags = $repository->select2Find();

        return $this->render('tags/tags.json.twig', ['tags' => $tags]);
    }

    /**
     * @param Request $request
     *
     * @Route("/autocomplete", name="ajax_autocomplete")
     *
     * @return JsonResponse
     */
    public function autocompleteAction(Request $request)
    {
        // Check security etc. if needed

        $result = $this->autocomplete->getAutocompleteResults($request, WalkFormType::class);

        return new JsonResponse($result);
    }

}