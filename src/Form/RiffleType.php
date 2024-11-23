<?php

namespace App\Form;


use App\Entity\Riffle;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RiffleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('amount', TextType::class, [
                'label' => 'Betrag',
                'required' => true,
                'attr' => [
                    'placeholder' => 'Betrag'
                ]
            ])
            ->add('acquiredName', TextType::class, [
                'required' => true,
                'label' => 'app.employee.name',
                'attr' => [
                    'placeholder' => 'Name'
                ]
            ])
            ->add('acquiredFirstname', TextType::class, [
                'required' => true,
                'label' => 'app.employee.firstname',
                'attr' => [
                    'placeholder' => 'Vorname'
                ]
            ])
            ->add('acquiredEntry', DateType::class, [
                'widget' => 'single_text',
                'required' => true,
                'label' => 'Eintrittsdatum'
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
            ->add('acquiredHours', CheckboxType::class, [
                'required' => false,
                'label' => 'variable Zeiten'
            ])
            ->add('info', TextareaType::class, [
                'label' => 'zusätzliche Information',
                'required' => false,
                'attr' => [
                    'placeholder' => 'zusätzliche Information',
                ]
            ])
            ->add('removeEntryRiffle', ButtonType::class, [
                'label' => 'Riffle entfernen',
                'attr' => [
                    'class' => 'btn btn-conuti-remove js-remove-riffle',
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Riffle::class
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
