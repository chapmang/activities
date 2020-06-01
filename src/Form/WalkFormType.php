<?php


namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\DataTransformer\NumberToLocalizedStringTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Tetranz\Select2EntityBundle\Form\Type\Select2EntityType;
use Symfony\Component\Routing\RouterInterface;

class WalkFormType extends AbstractType
{
    private $router;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class)
            ->add('shortDescription', TextType::class, [
                'required' => false
            ])
            ->add('description', TextareaType::class, [
                'required' => false,
                'rows' => 30
            ])
            ->add('location', TextType::class, [
                'required' => false
            ] )
            ->add('distance', NumberType::class, [
                'required' => false,
                'scale' => 2,
                'rounding_mode' => NumberToLocalizedStringTransformer::ROUND_HALF_DOWN
            ])
            ->add('minimumTimeHours', IntegerType::class, [
                'required' => false
            ])
            ->add('minimumTimeMinutes', IntegerType::class, [
                'required' => false
            ])
            ->add('ascent', IntegerType::class, [
                'required' => false
            ])
            ->add('gradient', ChoiceType::class, [
                'required' => false,
                'choices' => [
                    '0' => 0,
                    '1' => 1,
                    '2' => 2,
                    '3' => 3
                ]
            ])
            ->add('difficulty', ChoiceType::class, [
                'required' => false,
                'choices' => [
                    '1' => 1,
                    '2' => 2,
                    '3' => 3
                ]
            ])
            ->add('paths', TextType::class, [
                'required' => false
            ])
            ->add('landscape', TextType::class, [
                'required' => false
            ])
            ->add('dogFriendliness', TextType::class, [
                'required' => false
            ])
            ->add('parking', TextType::class, [
                'required' => false
            ])
            ->add('publicToilet', TextType::class, [
                'required' => false
            ])
            ->add('notes', TextType::class, [
                'required' => false
            ])
            ->add('suggestedMap', TextType::class, [
                'required' => false
            ])
            ->add('tags', Select2EntityType::class, [
                'multiple' => true,
                'remote_route' => 'ajax_autocomplete',
                'class' => 'App\Entity\Tag',
                'primary_key' => 'id',
                'text_property' => 'name',
                'allow_clear' => true,
                'placeholder' => 'Select Tags to describe this activity',
                'cache' => true,
                'cache_timeout' => 60000,
            ])
            ->add('directions', CollectionType::class, [
                'entry_type' => DirectionEmbeddedForm::class,
                'entry_options' => ['label' => false],
                'allow_delete' => true,
                'allow_add' => true,
                'by_reference' => false
            ])
            ->add('whereToEatAndDrink', TextareaType::class, [
                'required' => false
            ])
            ->add('whatToLookOutFor', TextareaType::class, [
                'required' => false,
                'label' => 'What to see'
            ])
            ->add('whileYouAreThere', TextareaType::class, [
                'required' => false
            ]);
    }

//    public function configureOptions(OptionsResolver $resolver)
//    {
//        $resolver->setDefaults([
//            'data_class' => WalkCreateModel::class
//        ]);
//    }


}