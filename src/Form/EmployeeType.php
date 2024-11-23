<?php

namespace App\Form;

use App\Entity\Bonus;
use App\Entity\Employee;
use App\Entity\Goodies;
use App\Entity\HourlyRate;
use App\Entity\Hours;
use App\Entity\IncentiveRate;
use App\Entity\Riffle;
use App\Entity\VariablePortion;
use App\Repository\BenefitsTypeRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EmployeeType extends AbstractType
{
    public function __construct(
        private readonly BenefitsTypeRepository $benefitsTypeRepository,
    ) {}

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $employee = new Employee();

        $benefitTypes = $this->benefitsTypeRepository->findAll();
        $benefitsType = [];
        $benefitTypeChoises = [];
        $benefitsMonetary = [];
        $benefitsPrivatePortion = [];
        if (isset($options["data"]) && $options["data"]["employee"] instanceof Employee) {
            $employee = $options["data"]["employee"];
            $benefits = $employee->getBenefits();
            foreach ($benefits as $benefit) {
                $benefitsType[$benefit->getBenefitsType()->getName()] = $benefit->getBenefitsType()->getId();
                $benefitsMonetary[$benefit->getBenefitsType()->getId()] = $benefit->getMonetaryBenefit();
                $benefitsPrivatePortion[$benefit->getBenefitsType()->getId()] = $benefit->getPrivatePortion();
            }

            foreach ($benefitTypes as $benefitType) {
                if($benefitType->isMonetaryBenefit()) {
                    $monetaryValue = "";
                    $benefitsPrivatePortionValue = "";
                    if(isset($benefitsMonetary[$benefitType->getId()])){
                        $monetaryValue = $benefitsMonetary[$benefitType->getId()];
                    }
                    if(isset($benefitsPrivatePortion[$benefitType->getId()])){
                        $benefitsPrivatePortionValue = $benefitsPrivatePortion[$benefitType->getId()];
                    }
                    $builder
                        ->add('benefitsMonetaryBenefit'.$benefitType->getId(), TextType::class, [
                            'required' => false,
                            'label' => 'geldwerter Vorteil',
                            'mapped' => false,
                            'attr' => [
                                'value' => $monetaryValue,
                                'placeholder' => 'geldwerter Vorteil'
                            ]
                        ])
                        ->add('benefitsPrivatePortion'.$benefitType->getId(), TextType::class, [
                            'required' => false,
                            'label' => 'privater Anteil',
                            'mapped' => false,
                            'attr' => [
                                'value' => $benefitsPrivatePortionValue,
                                'placeholder' => 'privater Anteil'
                            ]
                        ]);
                }
                $benefitTypeChoises[$benefitType->getName()] = $benefitType->getId();
            }

            $builder
                ->add(
                    $builder->create('benefitsGroup', FormType::class, [
                        'inherit_data' => true,
                        'label' => 'app.benefits',
                    ])
                        ->add('benefits', ChoiceType::class, [
                            'required' => false,
                            'label' => 'app.benefits',
                            'multiple' => true,
                            'expanded' => false,
                            'choices' => $benefitTypeChoises,
                            'data' => $benefitsType,
                        ])
                )
                ->add(
                    $builder->create('variablePortionGroup', FormType::class, [
                        'inherit_data' => true,
                        'label' => "Ausbezahlt " . $options["data"]["payoutMonth"],
                    ])
                        ->add('variablePortion', TextType::class, [
                            'label' => 'app.employee.variable',
                            'disabled' => true,
                            'required' => false,
                            'attr' => [
                                'placeholder' => 'app.employee.variable'
                            ]
                        ])
                )
                ->add('hours', CollectionType::class, [
                    'entry_type' => HoursType::class,
                    'allow_add' => true,
                    'allow_delete' => true,
                    'by_reference' => false,
                    'prototype' => true,
                    'label_attr' => ['class' => 'hoursLabel']
                ])
                ->add('riffles', CollectionType::class, [
                    'entry_type' => RiffleType::class,
                    'allow_add' => true,
                    'allow_delete' => true,
                    'by_reference' => false,
                    'prototype' => true,
                    'label_attr' => ['class' => 'riffleLabel']
                ])
                ->add('goodies', CollectionType::class, [
                    'entry_type' => GoodiesType::class,
                    'allow_add' => true,
                    'allow_delete' => true,
                    'by_reference' => false,
                    'prototype' => true,
                    'label_attr' => ['class' => 'goodiesLabel']
                ])
                ->add('bonuses', CollectionType::class, [
                    'entry_type' => BonusType::class,
                    'allow_add' => true,
                    'allow_delete' => true,
                    'by_reference' => false,
                    'prototype' => true,
                    'label_attr' => ['class' => 'bonusLabel']
                ])
            ;
        }
        $builder
            ->add(
                $builder->create('personalGroup', FormType::class, [
                    'inherit_data' => true,
                    'label' => 'app.employee.personal.data',
                    ])
                    ->add('name', TextType::class, [
                        'label' => 'app.employee.name',
                        'attr' => [
                            'value' => $employee->getName(),
                            'placeholder' => 'app.employee.name'
                        ]
                    ])
                    ->add('firstname', TextType::class, [
                        'label' => 'app.employee.firstname',
                        'attr' => [
                            'value' => $employee->getFirstname(),
                            'placeholder' => 'app.employee.firstname'
                        ]
                    ])
                    ->add('employeeNumber', TextType::class, [
                        'label' => 'app.employee.number',
                        'attr' => [
                            'value' => $employee->getEmployeeNumber(),
                            'placeholder' => 'app.employee.number'
                        ]
                    ])
                    ->add('email', EmailType::class, [
                        'label' => 'Email',
                        'attr' => [
                            'value' => $employee->getEmail(),
                            'placeholder' => 'Email',
                        ]
                    ])
                    ->add('entry', DateType::class, [
                        'widget' => 'single_text',
                        'label' => 'app.employee.entry',
                        'required' => false,
                        'attr' => [
                            'value' => $employee->getEntry() ? $employee->getEntry()->format('Y-m-d') : $employee->getEntry(),
                        ]
                    ])
                    ->add('resignation', DateType::class, [
                        'widget' => 'single_text',
                        'label' => 'app.employee.exit',
                        'required' => false,
                        'attr' => [
                            'value' => $employee->getResignation() ? $employee->getResignation()->format('Y-m-d') : $employee->getResignation()
                        ]
                    ])
            )
            ->add(
                $builder->create('additionalGroup', FormType::class, [
                    'inherit_data' => true,
                    'label' => 'Zusätliche Daten',
                ])
                    ->add('hourlyRate', TextType::class, [
                        'label' => 'app.employee.hourly.rate',
                        'required' => false,
                        'attr' => [
                            'placeholder' => 'app.employee.hourly.rate'
                        ]
                    ])
                    ->add('incentiveRate', TextType::class, [
                        'label' => 'app.employee.incentiv',
                        'required' => false,
                        'attr' => [
                            'placeholder' => 'app.employee.incentiv'
                        ]
                    ])
                    ->add('annualWorkingTime', TextType::class, [
                        'label' => 'Jahresarbeitszeit',
                        'required' => false,
                        'attr' => [
                            'value' => $employee->getAnnualWorkingTime(),
                            'placeholder' => 'Jahresarbeitszeit'
                        ]
                    ])
            )
            ->add(
                $builder->create('salaryGroup', FormType::class, [
                    'inherit_data' => true,
                    'label' => 'Gehaltsbestandteile',
                    ])
                    ->add(
                        'targetSalary', TextType::class, [
                        'label' => 'app.employee.target.salary',
                        'required' => false,
                        'attr' => [
                            'value' => $employee->getTargetSalary(),
                            'placeholder' => 'app.employee.target.salary'
                        ]
                    ])
                    ->add('targetVariablePortion', TextType::class, [
                        'label' => 'app.employee.variable.rate',
                        'required' => false,
                        'attr' => [
                            'value' => $employee->getTargetVariablePortion(),
                            'placeholder' => 'app.employee.variable.rate'
                        ]
                    ])
                    ->add('fixPortion', TextType::class, [
                        'label' => 'app.employee.fix.rate',
                        'required' => false,
                        'attr' => [
                            'value' => $employee->getFixPortion(),
                            'placeholder' => 'app.employee.fix.rate'
                        ]
                    ])
                    ->add('fixVariablePortion', TextType::class, [
                        'label' => 'FixVariable',
                        'required' => false,
                        'attr' => [
                            'value' => $employee->getFixVariablePortion(),
                            'placeholder' => 'FixVariable'
                        ]
                    ])
                    ->add('fixVariableEntry', DateType::class, [
                        'widget' => 'single_text',
                        'label' => 'app.employee.fix.variable.entry',
                        'required' => false,
                        'attr' => [
                            'value' => $employee->getFixVariableEntry() ? $employee->getFixVariableEntry()->format('Y-m-d') : $employee->getFixVariableEntry(),
                        ]
                    ])
            )
            ->add('info', TextareaType::class, [
                'required' => false,
                'label' => 'zusätzliche Information',
                'data' => $employee->getInfo(),
                'attr' => [
                    'placeholder' => 'zusätzliche Information',
                    'rows' => 5
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'app.employee.submit',
                'attr' => [
                    'class' => 'btn-conuti float-end'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_classes' => [
                Employee::class,
                VariablePortion::class,
                HourlyRate::class,
                Hours::class,
                IncentiveRate::class,
                Riffle::class,
                Goodies::class,
                Bonus::class,
            ],
            'allow_extra_fields' => true,
            'csrf_protection' => true,
        ]);
    }
}
