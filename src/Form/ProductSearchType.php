<?php

namespace App\Form;

use App\Entity\ProductSearch;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('searchInput', TextType::class, [
                'required' => false,
                'attr' => [
                    'placeholder' => 'Rechercher une entrée'
                ]
            ])
            ->add('stock', ChoiceType::class, [
                'choices' => array_flip(ProductSearch::STOCK),
                'expanded' => true,
                'multiple' => true,
                'attr' => [
                    'class' => 'form-check-inline'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ProductSearch::class,
            'method' => 'get',
            'csrf_protection' => false,
            'translation_domain' => 'forms'
        ]);
    }

    public function getBlockPrefix()
    {
        return '';
    }
}
