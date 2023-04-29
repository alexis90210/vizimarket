<?php

namespace App\Form;

use App\Entity\ConfigurationPaiementCreateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ConfigurationPaiementCreateurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, ['label' => false])
            ->add('logo', FileType::class, ['label' => false])
            ->add('Description')
            // ->add('isActif')
            ->add('submit', SubmitType::class, ['label' => 'Creer maintenant']);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ConfigurationPaiementCreateur::class,
        ]);
    }
}
