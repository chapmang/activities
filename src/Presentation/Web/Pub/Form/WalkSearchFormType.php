<?php
declare(strict_types=1);
namespace App\Presentation\Web\Pub\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Tetranz\Select2EntityBundle\Form\Type\Select2EntityType;

class WalkSearchFormType  extends AbstractType
{
    private $router;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('string', TextType::class)
            ->add('tags', Select2EntityType::class, [
                'multiple' => true,
                'remote_route' => 'ajax_autocomplete',
                'class' => 'App\Domain\Entity\Tag',
                'primary_key' => 'id',
                'text_property' => 'name',
                'allow_clear' => true,
                'placeholder' => 'tags',
                'cache' => true,
                'cache_timeout' => 60000,

            ]);
    }
}