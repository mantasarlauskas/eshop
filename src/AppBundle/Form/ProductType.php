<?php

namespace AppBundle\Form;

use AppBundle\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, array(
                'attr' => array(
                    'autocomplete' => 'off'
                ),
                'label' => 'Pavadinimas'
            ))
            ->add('price', NumberType::class, array(
                'attr' => array(
                    'autocomplete' => 'off'
                ),
                'label' => 'Kaina'
            ))
            ->add('count', NumberType::class, array(
                'attr' => array(
                    'autocomplete' => 'off'
                ),
                'label' => 'Kiekis'
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Product::class,
        ));
    }
}