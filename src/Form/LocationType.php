<?php

namespace App\Form;

use App\Entity\Location;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LocationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('city', null, [
                'attr' => [
                    'placeholder' => 'Enter city name',
                ],
            ])
            ->add('country', ChoiceType::class, [
                'choices' => [
                    'Poland' => 'PL',
                    'Germany' => 'DE',
                    'France' => 'FR',
                    'Italy' => 'IT',
                    'Spain' => 'ES',
                    'United States' => 'US',
                    'Australia' => 'AU',
                    'United Kingdom' => 'GB',
                ]
            ])
            ->add('latitude', NumberType::class, [
                'scale' => 6,
                'attr' => [
                    'placeholder' => 'e.g. 52.2297',
                    'step' => '0.000001',
                    'min' => -90,
                    'max' => 90,
                ],
            ])
            ->add('longitude', NumberType::class, [
                'scale' => 6,
                'attr' => [
                    'placeholder' => 'e.g. 52.2297',
                    'step' => '0.000001',
                    'min' => -90,
                    'max' => 90,
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Location::class,
        ]);
    }
}
