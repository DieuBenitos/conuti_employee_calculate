<?php

namespace App\Form;


use App\Entity\Goodies;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Event\PostSetDataEvent;
use Symfony\Component\Form\Event\PostSubmitEvent;
use Symfony\Component\Form\Event\PreSetDataEvent;
use Symfony\Component\Form\Event\PreSubmitEvent;
use Symfony\Component\Form\Event\SubmitEvent;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GoodiesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
//            ->add('employee', EntityType::class, [
//                'class' => Employee::class,
//                'choice_label' => function (Employee $employee): string {
//                    return $employee->getFirstName(). " " .$employee->getName();
//                },
//                'multiple' => false,
//                'expanded' => false,
//            ])
            ->add('designation', TextType::class, [
                'label' => 'Bezeichnung',
                'required' => true,
                'attr' => [
                    'placeholder' => 'Bezeichnung'
                ]
            ])
            ->add('totalAmount', NumberType::class, [
                'required' => true,
                'label' => 'Betrag',
                'attr' => [
                    'placeholder' => 'Betrag'
                ]
            ])
            ->add('divider', NumberType::class, [
                'required' => true,
                'label' => 'Teiler/Raten',
                'attr' => [
                    'placeholder' => 'Teiler'
                ]
            ])
            ->add('charged', CheckboxType::class, [
                'required' => false,
                'label' => 'Bezahlt für diesen Monat'
            ])
            ->add('partialAmounts', TextType::class, [
                'required' => true,
                'label' => 'Teilbeträge',
                'attr' => [
                    'placeholder' => 'Teilbeträge'
                ]
            ])
            ->add('month', ChoiceType::class, [
                'expanded' => false,
                'multiple' => false,
                'choices' => $this->getMonths(),
                'label' => 'Monat angelegt'
            ])
            ->add('year', ChoiceType::class, [
                'expanded' => false,
                'multiple' => false,
                'choices' => $this->getYears(2015),
                'label' => 'Jahr angelegt'
            ])
            ->add('changed', CheckboxType::class, [
                'required' => false,
                'label' => 'Änderung durchgeführt'
            ])
            ->add('info', TextareaType::class, [
                'label' => 'zusätzliche Information',
                'required' => false,
                'attr' => [
                    'placeholder' => 'zusätzliche Information',
                ]
            ])
            ->add('removeEntryGoody', ButtonType::class, [
                'label' => 'Goody entfernen',
                'attr' => [
                    'class' => 'btn btn-conuti-remove js-remove-goodies',
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Goodies::class
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
