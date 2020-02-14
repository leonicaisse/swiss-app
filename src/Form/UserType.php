<?php

namespace App\Form;

use App\Entity\Address;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username')
            ->add('email', EmailType::class)
            ->add('roles', ChoiceType::class, [
                'choices' => User::ROLES,
                'choice_label' => function ($choice, $key, $value) {
                    return User::ROLES[$key];
                },
                'expanded' => true,
                'multiple' => true,
                'attr' => [
                    'class' => 'form-check-inline'
                ]
            ])
            ->add('firstname')
            ->add('lastname')
            ->add('phone')
            ->add('service')
            ->add('address', EntityType::class, [
                'label' => 'Adresse',
                'class' => Address::class,
                'choice_label' => function (Address $address) {
                    return $address->getName() . ' (' . $address->getAddressLine1() . ', ' . $address->getPostalCode() . ' ' . $address->getCity() . ')';
                },
                'multiple' => false,
                'attr' => [
                    'class' => 'select-address'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'csrf_protection' => false,
            'translation_domain' => 'forms'
        ]);
    }
}
