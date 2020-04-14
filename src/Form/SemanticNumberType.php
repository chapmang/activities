<?php
namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\DataTransformer\NumberToLocalizedStringTransformer;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SemanticNumberType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('semanticNumber', NumberType::class, [
                'scale' => 2,
                'rounding_mode' => NumberToLocalizedStringTransformer::ROUND_HALF_DOWN,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
       $resolver->setDefaults([
           'semantic_label' => null
       ]);
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['semanticLabel'] = $options['semantic_label'];
    }
}