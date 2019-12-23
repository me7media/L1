<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Product;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('id')
            ->add('name')
            ->add('category', EntityType::class, [
                // looks for choices from this entity
                'class'       => Category::class,
                'placeholder' => 'Выберите категорию',
            ])
            ->add('price')
            ->add('qt')
            ->add('goOnSale', DateType::class, [
                'attr' => [
                    'min'   => (new \DateTime('tomorrow'))->format('Y-m-d'),
                    'value' => (new \DateTime('tomorrow'))->format('Y-m-d'),
                ],
                'widget' => 'single_text',
            ])
            ->add('created_at', DateTimeType::class, [
                'attr' => [
                    'readonly' => true,
                    'min'      => (new \DateTime('now'))->format('Y-m-d H:i:s'),
                    'value'    => (new \DateTime('now'))->format('Y-m-d H:i:s'),
                ],
//                'disabled' => 'disabled',
                'widget' => 'single_text',
            ])
            ->add('save', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
