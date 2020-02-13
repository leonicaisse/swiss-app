<?php

namespace App\Form;

use App\Entity\Address;
use App\Entity\AddressSearch;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddressSearchType extends AbstractType
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
            ->add('searchBy', ChoiceType::class, [
                'choices' => array_flip(AddressSearch::SEARCH_BY)
            ])
            ->add('orderBy', ChoiceType::class, [
                'choices' => array_flip(AddressSearch::ORDER_BY)
            ])
            ->add('orderDirection', ChoiceType::class, [
                'choices' => [
                    "DESC" => "DESC",
                    "ASC" => "ASC"
                ],
                'expanded' => true,
                'multiple' => false,
                'attr' => [
                    'class' => 'form-check-inline',
                ]
            ]);;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => AddressSearch::class,
            'method' => 'get',
            'csrf_protection' => false,
            'translation_domain' => 'forms'
        ]);
    }
}
