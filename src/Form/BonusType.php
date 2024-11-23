<?php

namespace App\Form;


use App\Entity\Bonus;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BonusType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('amount', TextType::class, [
                'label' => 'Betrag',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Bezeichnung'
                ]
            ])
            ->add('percent', TextType::class, [
                'required' => false,
                'label' => 'Prozent',
                'attr' => [
                    'placeholder' => 'Betrag'
                ]
            ])
            ->add('description', TextareaType::class, [
                'required' => false,
                'label' => 'Beschreibung',
                'attr' => [
                    'placeholder' => 'Teiler'
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
                'label' => 'app.year'
            ])
            ->add('info', TextareaType::class, [
                'label' => 'zusätzliche Information',
                'required' => false,
                'attr' => [
                    'placeholder' => 'zusätzliche Information',
                ]
            ])
            ->add('add', ChoiceType::class, [
                'required' => true,
                'expanded' => true,
                'multiple' => false,
                'choices' => [
                    'addiert' => true,
                    'abgezogen' => false,
                ],
            ])
            ->add('removeEntryBonus', ButtonType::class, [
                'label' => 'Bonus entfernen',
                'attr' => [
                    'class' => 'btn btn-conuti-remove js-remove-bonus',
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Bonus::class
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
