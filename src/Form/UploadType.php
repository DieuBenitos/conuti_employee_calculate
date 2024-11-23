<?php

namespace App\Form;

use App\Entity\Employee;
use App\Entity\Hours;
use App\Repository\HoursRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Event\PreSubmitEvent;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateIntervalType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints\File;

class UploadType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        if ($options["data_class"] === Hours::class) {
            $builder
                ->add('month', ChoiceType::class, [
                    'expanded' => false,
                    'multiple' => false,
                    'choices' => $this->getMonths(),
                    'label' => 'Monat'
                ])
                ->add('year', DateType::class, [
                    'widget' => 'choice',
                    'years' => range(date('Y'), date('Y')-8),
                    'label' => 'app.year',
                    'input' => 'array',
                    'required' => true,
                    'placeholder' => 'bitte auswählen',
                    'mapped' => false,
                ])
                ->add('checkIfImported', CheckboxType::class, [
                    'label' => 'Trotzdem Importieren',
                    'attr' => [
                        'class' => 'btn btn-conuti float-end'
                    ],
                    'mapped' => false,
                    'required' => false,
                ])
            ;
        }

        $builder
            ->add('import', FileType::class, [
                'label' => 'Import (Excel-Datei)',
                'mapped' => false,
                'required' => true,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'application/vnd.ms-excel',
                            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid Excel document',
                    ])
                ],
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Importieren',
                'attr' => [
                    'class' => 'btn btn-conuti float-end'
                ],
            ]);;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_classes' => [Hours::class, Employee::class]
        ]);
    }

    private function getMonths(): array
    {
        return [
            'bitte auswählen' => 0,
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
