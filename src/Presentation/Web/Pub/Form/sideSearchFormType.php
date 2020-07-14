<?php
declare(strict_types=1);
namespace App\Presentation\Web\Pub\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Tetranz\Select2EntityBundle\Form\Type\Select2EntityType;

class sideSearchFormType  extends AbstractType
{
    private $router;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('string', SearchType::class, [
                'label' => false,
                'required' => false,
                'attr' => ['placeholder' => 'Search']
            ])
            ->add('tags', Select2EntityType::class, [
                'multiple' => true,
                'remote_route' => 'ajax_autocomplete',
                'class' => 'App\Domain\Entity\Tag',
                'primary_key' => 'id',
                'text_property' => 'name',
                'allow_clear' => true,
                'placeholder' => 'Tags',
                'cache' => true,
                'cache_timeout' => 60000,
                'label' => false,
            ])
            ->add('search', SubmitType::class, [
                'label' => 'Search',
                'attr' => ['class' => 'ui fluid primary']
            ]);
    }
}