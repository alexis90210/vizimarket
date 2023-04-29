<?php

namespace App\Form;

use App\Entity\ProduitSimple;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddProduitSimpleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class)
            ->add('tarif_regulier', TextType::class)
            ->add('tarif_promo', TextType::class)
            ->add('image', FileType::class, ['required' => false])
            ->add('description', TextType::class)
            ->add('longueur', NumberType::class, ['required' => false])
            ->add('largeur', NumberType::class, ['required' => false])
            ->add('hauteur', NumberType::class, ['required' => false])
            ->add('poids', NumberType::class, ['required' => false])
            ->add('categorie', ChoiceType::class, [
                'required' => false,
                'multiple' => true,
            ])
            ->add('etiquette', ChoiceType::class, ['mapped' => false])

            ->add('submit', SubmitType::class, ['label' => 'Creer']);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ProduitSimple::class,
        ]);
    }
}
