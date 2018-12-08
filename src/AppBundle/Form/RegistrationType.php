<?php
/**
 * Created by PhpStorm.
 * User: mantas
 * Date: 18.12.6
 * Time: 14.05
 */

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class, array(
                'attr' => array(
                    'autocomplete' => 'off'
                ),
                'label' => 'Vartotojo vardas'
            ))
            ->add('email', EmailType::class, array(
                'attr' => array(
                    'autocomplete' => 'off'
                ),
                'label' => 'El. paštas'
            ))
            ->add('password', PasswordType::class, array(
                'label' => 'Slaptažodis'
            ))
            ->add('roles', ChoiceType::class, array(
                'mapped' => false,
                'required' => true,
                'label'    => 'Vartotojo tipas',
                'choices' => array(
                    'Klientas' => 'ROLE_USER',
                    'Vadybininkas' => 'ROLE_ADMIN',
                    'Administratorius' => 'ROLE_SUPER_ADMIN',
                ),
                'expanded' => true,
            ));
    }

}