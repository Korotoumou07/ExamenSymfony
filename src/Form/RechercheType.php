<?php

namespace App\Form;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class RechercheType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('telephone', TextType::class, [
            'label' => "Client",
            'required' => false,
            'attr' => [
                'placeholder' => 'Rechercher le client par téléphone',
                'class' => 'form-control',
            ],
            'constraints' => [
                new Assert\NotBlank(['message' => 'Le numero est obligatoire.']),
               
            ],
        ])
        ->add('submit', SubmitType::class, [
            'label' => 'Rechercher',
            'attr' => [
                'class' => 'btn btn-primary',
            ],
        ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
