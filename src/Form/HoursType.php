<?php

namespace App\Form;

use App\Entity\Hours;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class HoursType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('variableHours', TextType::class, [
                'label' => 'variable Stunden',
                'required' => false,
                'attr' => [
                    'placeholder' => 'variable Stunden'
                ]
            ])
            ->add('month', ChoiceType::class, [
                'expanded' => false,
                'multiple' => false,
                'choices' => $this->getMonths(),
                'label' => 'Monat'
            ])
            ->add('year', ChoiceType::class, [
                'expanded' => false,
                'multiple' => false,
                'choices' => $this->getYears(2015),
                'label' => 'Jahr'
            ])
            ->add('info', TextareaType::class, [
                'label' => 'zusätzliche Information',
                'required' => false,
                'attr' => [
                    'placeholder' => 'zusätzliche Information',
                ]
            ])
            ->add('removeEntryHours', ButtonType::class, [
                'label' => 'Stunden entfernen',
                'attr' => [
                    'class' => 'btn btn-conuti-remove js-remove-hours',
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Hours::class
        ]);
    }

    private function getYears(int $min,string $max = 'current'): array
    {
        $years = range($min, ($max === 'current' ? date('Y') : $max));
        return array_combine($years, $years);
    }

    private function getMonths(): array
    {
        return [
            'Januar'   => 1,
            'Februar'  => 2,
            'März'     => 3,
            'April'     => 4,
            'Mai'       => 5,
            'Juni'      => 6,
            'Juli'      => 7,
            'August'    => 8,
            'September' => 9,
            'Oktober'   => 10,
            'November'  => 11,
            'Dezember'  => 12,
        ];
    }
}
