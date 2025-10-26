<?php

namespace App\Form;

use App\Entity\Attributes;
use App\Entity\Measurement;
use App\Entity\Value;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ValueType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('measurement', EntityType::class, [
                'class' => Measurement::class,
                'choice_label' => function (Measurement $m) {
                    $city = $m->getLocation()?->getCity() ?? 'brak miasta';
                    $country = $m->getLocation()?->getCountry() ?? '';
                    $date = $m->getDate()?->format('Y-m-d') ?? 'brak daty';
                    return sprintf('%s — %s, %s', $date, $city, $country);
                },
                'placeholder' => 'Wybierz pomiar',
            ])
            ->add('attribute', EntityType::class, [
                'class' => Attributes::class,
                'choice_label' => function (Attributes $a) {
                    return sprintf('%s (%s)', $a->getName(), $a->getUnit());
                },
                'placeholder' => 'Wybierz atrybut',
            ])
            ->add('value', NumberType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Value::class,
        ]);
    }
}
