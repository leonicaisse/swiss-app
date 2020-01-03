<?php

namespace App\Form;

use App\Entity\Command;
use App\Entity\Product;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommandType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('reference')
            ->add('products', EntityType::class, [
                'class' => Product::class,
                'choice_label' => 'reference',
                'multiple' => true
            ])
            ->add('quantity')
            ->add('file', FileType::class, [
                'required' => false
            ])
            ->add('state', ChoiceType::class, [
                'choices' => array_flip(Command::STATE)
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Command::class,
            'translation_domain' => 'forms.command'
        ]);
    }
}
