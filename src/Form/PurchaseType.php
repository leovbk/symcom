<?php

namespace App\Form;

use App\Entity\Purchase;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class PurchaseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('fullName', TextType::class, [
                'required' => false,
                'label' => 'Nom complet',
                'attr' => [
                    'placeholder' => 'Saisir votre nom'
                ]
            ])
            ->add('address', TextType::class, [
                'required' => false,
                'label' => 'Adresse complète',
                'attr' => [
                    'placeholder' => 'Saisissez votre adresse'
                ]
            ])
            ->add('postalCode', TextType::class, [
                'required' => false,
                'label' => 'Code postal',
                'attr' => [
                    'placeholder' => 'Saisissez votre code postal'
                ]
            ])
            ->add('city', TextType::class, [
                'required' => false,
                'label' => 'Ville',
                'attr' => [
                    'placeholder' => 'Saisissez votre ville'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Purchase::class,
        ]);
    }
}