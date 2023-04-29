<?php

namespace App\Form;

use App\Entity\Vazimarket;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VazimarketType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email',EmailType::class, ['label' => false, 'required' => true])
            // ->add('roles')
            ->add('password',PasswordType::class, ['label' => false, 'required' => true])
            ->add('submit', SubmitType::class, ['label' => 'Inscription']);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Vazimarket::class,
        ]);
    }
}
