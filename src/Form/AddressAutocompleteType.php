<?php

namespace App\Form;

use App\Entity\Address;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddressAutocompleteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('autocomplete', TextType::class, [
                'label' => false,
                'mapped' => false,
                'attr' => [ 'class' => "addressAutocomplete" ]
            ])
            ->add('longitude', HiddenType::class)
            ->add('latitude', HiddenType::class)
            ->add('streetNumber', HiddenType::class)
            ->add('street', HiddenType::class)
            ->add('zipCode', HiddenType::class)
            ->add('city', HiddenType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
            'data_class' => Address::class
        ]);
    }
}
