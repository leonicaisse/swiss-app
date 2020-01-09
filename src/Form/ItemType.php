<?php

namespace App\Form;

use App\Entity\Item;
use App\Entity\Product;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
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
                    'class' => 'form-check-inline'
                ]
            ])
            ->add('orderedQuantity', IntegerType::class, [
                'attr' => [
                    'class' => 'form-check-inline'
                ]
            ])
            ->add('file', FileType::class, [
                'required' => false,
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
