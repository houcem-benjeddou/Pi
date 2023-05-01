<?php

namespace App\Form;

use App\Entity\Commande;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Length;

class CommandeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('Nom', null, [
            'constraints' => [
                new NotBlank(['message' => 'Le nom est obligatoire']),
                new Regex(['pattern' => '/^[a-zA-Z]*$/', 'message' => 'Le nom ne doit contenir que des lettres']),
            ]
        ])
        ->add('Prenom', null, [
            'constraints' => [
                new NotBlank(['message' => 'Le prénom est obligatoire']),
                new Regex(['pattern' => '/^[a-zA-Z]*$/', 'message' => 'Le prénom ne doit contenir que des lettres']),
            ]
        ])
        ->add('Adresse', null, [
            'constraints' => [
                new NotBlank(['message' => 'L\'adresse est obligatoire']),
                new Email(['message' => 'L\'adresse email est invalide']),
            ]
        ])
        ->add('Telephone', null, [
            'constraints' => [
                new NotBlank(['message' => 'Le téléphone est obligatoire']),
                new Length(['exactMessage' => 'Le téléphone doit contenir exactement {{ limit }} chiffres', 'min' => 8, 'max' => 8]),
                new Regex(['pattern' => '/^[0-9]*$/', 'message' => 'Le téléphone ne doit contenir que des chiffres']),
            ]
        ])
        ->add('PrixTotal', null, [
            'constraints' => [
                new NotBlank(['message' => 'Le prix total est obligatoire']),
            ]
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Commande::class,
        ]);
    }
}
