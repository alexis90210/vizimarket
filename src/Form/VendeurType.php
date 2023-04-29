<?php

namespace App\Form;

use App\Entity\Vendeur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VendeurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => false,
                'required' => true
            ])
            // ->add('roles')
            ->add('password', PasswordType::class, [
                'label' => false,
                'required' => true
            ])
            ->add('Nom', TextType::class, [
                'label' => false,
                'required' => true
            ])
            ->add('Mobile',TextType::class, [
                'label' => false,
                'required' => true
            ])
            // ->add('Description')
            // ->add('ImageProfile')
            // ->add('PhotoCouverture')
            // ->add('Adresse')
            ->add('Pays', TextType::class, [
                'label' => false,
                'required' => true
            ])
            // ->add('createur')
            ->add('submit', SubmitType::class, ['label' => 'Inscription']);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Vendeur::class,
        ]);
    }
}
