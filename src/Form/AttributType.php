<?php

namespace App\Form;

use App\Entity\Attribut;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AttributType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, ['label' => false, 'required' => true])
            ->add('type', ChoiceType::class, [
                'mapped' => false,
                'choices' => [
                    'selectionner le type' => null,
                    'Couleur' => "Couleur",
                    'Taille' => "Taille",
                    'Label' => "Label"
                ]
            ])
            ->add('submit', SubmitType::class, ['label' => 'Creer']);;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Attribut::class,
        ]);
    }
}
