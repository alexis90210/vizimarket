<?php

namespace App\Form;

use App\Entity\Createur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CreateurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email',EmailType::class)
            // ->add('roles')
            ->add('password',PasswordType::class)
            ->add('nom',TextType::class, [
                'label' => false,
                'required' => true
            ])
            ->add('prenom',TextType::class, [
                'label' => false,
                'required' => true
            ])
            // ->add('dateInscription')
            // ->add('Logo')
            // ->add('ConditionGeneralVente')
            // ->add('MentionLegale')
            // ->add('ConditionGeneralUtilisation')
            // ->add('CommissionVendeur')
            // ->add('SitePoliceLink')
            // ->add('SitePoliceNom')
            // ->add('typeValidationVendeur')
            ->add('submit', SubmitType::class, ['label' => 'Inscription']);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Createur::class,
        ]);
    }
}
