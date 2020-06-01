<?php


namespace App\Service;


use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PropertyAccess\PropertyAccess;

class Autocomplete
{
    /**
     * @var Request
     */
    private $request;
    private $type;
    private $formFactory;
    /**
     * @var ManagerRegistry
     */
    private $doctrine;

    public function __construct(FormFactoryInterface $formFactory, ManagerRegistry $doctrine)
    {
        $this->formFactory = $formFactory;
        $this->doctrine = $doctrine;
    }

    /**
     * @param Request $request
     * @param $type
     * @return array
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function getAutocompleteResults(Request $request, $type) {

        $form = $this->formFactory->create($type);
        $fieldOptions = $form->get($request->get('field_name'))->getConfig()->getOptions();

        /** @var EntityRepository $repo */
        $repo = $this->doctrine->getRepository($fieldOptions['class']);

        $term = $request->get('q');

        $countQB = $repo->createQueryBuilder('e');
        $countQB
            ->select($countQB->expr()->count('e'))
            ->where('e.'.$fieldOptions['text_property'].' LIKE :term')
            ->setParameter('term', '%' . $term . '%');

        $maxResults = $fieldOptions['page_limit'];
        $offset = ($request->get('page', 1) - 1) * $maxResults;

        $resultQb = $repo->createQueryBuilder('e');
        $resultQb
            ->where('e.'.$fieldOptions['text_property'].' LIKE :term')
            ->setParameter('term', '%' . $term . '%')
            ->setMaxResults($maxResults)
            ->setFirstResult($offset);

        if (is_callable($fieldOptions['callback'])) {
            $cb = $fieldOptions['callback'];

            $cb($countQB, $request);
            $cb($resultQb, $request);
        }

        $count = $countQB->getQuery()->getSingleScalarResult();
        $paginationResults = $resultQb->getQuery()->getResult();

        $result = ['results' => null, 'more' => $count > ($offset + $maxResults)];

        $accessor = PropertyAccess::createPropertyAccessor();

        $result['results'] = array_map(function ($item) use ($accessor, $fieldOptions) {
            return [
                'id' => $accessor->getValue($item, $fieldOptions['primary_key']),
                'text' => $accessor->getValue($item, $fieldOptions['text_property'])
            ];
        }, $paginationResults);

        return $result;

    }
}