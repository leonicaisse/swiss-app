<?php

namespace App\Form;

use App\Entity\Address;
use App\Entity\Item;
use App\Entity\Product;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ItemType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('product', EntityType::class, [
                'label' => 'Product',
                'class' => Product::class,
                'choice_label' => 'reference',
                'multiple' => false,
                'attr' => [
                    'class' => 'select-product'
                ]
            ])
            ->add('orderedQuantity', IntegerType::class, [
                'attr' => [
                    'class' => 'col'
                ]
            ])
            ->add('file', FileType::class, [
                'required' => false,

            ])
            ->add('estimatedDelivery', DateType::class, [
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
                'required' => false
            ])
            ->add('realDelivery', DateType::class, [
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
                'required' => false
            ])
            ->add('deliverTo', EntityType::class, [
                'label' => 'Livrer à',
                'class' => User::class,
                'choice_label' => function (User $user) {
                    return $user->getFullname() . ' ( ' . $user->getAddress()->getName(). ' ) ';
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
            'data_class' => Item::class,
            'translation_domain' => 'forms.item'
        ]);
    }
}
