<?php

namespace App\Form;

use App\Entity\Annonce;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class AnnonceType extends AbstractType
{
    private $authorizationChecker;

    public function __construct(AuthorizationCheckerInterface $authorizationChecker)
    {
        $this->authorizationChecker = $authorizationChecker;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('description')
            ->add('imageUrl')
            ->add('price')
            ->add('sold')
            ->add('slug')
            ->add('status', ChoiceType::class, [
                'choices' => [
                    'Très mauvais' => Annonce::STATUS_VERY_BAD,
                    'Mauvais' => Annonce::STATUS_BAD,
                    'Bon' => Annonce::STATUS_GOOD,
                    'Très bon' => Annonce::STATUS_VERY_GOOD,
                    'Parfait' => Annonce::STATUS_PERFECT
                ]
            ])
            ->add('createdAt',DateType::class, [
                'widget' => 'single_text', //tu peux lire https://symfony.com/doc/current/reference/forms/types/date.html#rendering-a-single-html5-text-box
            ])
            ->add('tags', TagType::class, [
                'data_class' => null, // c'est la propriété dans l'entitié Tag qui sera affiché dans le select
                'allow_extra_fields' => true
            ])
        ;
        if ($this->authorizationChecker->isGranted('ROLE_ADMIN')) {
            $builder
                ->add('sold')
                ->add('createdAt',DateType::class, [
                    'widget' => 'single_text',
                ])
                ->add('slug')
            ;
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Annonce::class,
        ]);
    }

}

