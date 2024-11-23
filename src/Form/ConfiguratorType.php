<?php

namespace App\Form;


use App\Repository\EmployeeRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Validator\Constraints\GreaterThan;

class ConfiguratorType extends AbstractType
{
    public function __construct(
      private readonly EmployeeRepository $employeeRepository,
    ) {}
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $hasAccess = $options["data"]["hasAccess"];
//        if ($hasAccess) {
            $builder
                ->add('employees', ChoiceType::class, [
                        'label' => 'app.employee',
                        'expanded' => false,
                        'multiple' => true,
                        'choices' => $this->getEmployees(),
                    ]
                );
//        }
        $builder
            ->add(
                $builder->create('dateGroup', FormType::class, [
                    'inherit_data' => true,
                    'label' => 'Wunsch Monat und Jahr',
                ])
                    ->add('fromMonths', ChoiceType::class, [
                        'expanded' => false,
                        'multiple' => false,
                        'required' => true,
                        'choices' => $this->getMonths(),
                        'placeholder' => 'bitte auswählen',
                        'label' => 'Von Monat'
                    ])
                    ->add('fromYears', DateType::class, [
                        'widget' => 'choice',
                        'years' => range(date('Y'), date('Y')-8),
                        'label' => 'app.from.year',
                        'input' => 'array',
                        'required' => true,
                        'placeholder' => 'bitte auswählen',
                        'mapped' => false,
                    ])
                    ->add('toMonths', ChoiceType::class, [
                        'expanded' => false,
                        'multiple' => false,
                        'required' => false,
                        'choices' => $this->getMonths(),
                        'placeholder' => 'bitte auswählen',
                        'label' => 'Bis Monat'
                    ])
                    ->add('toYears', DateType::class, [
                        'widget' => 'choice',
                        'years' => range(date('Y'), date('Y')-8),
                        'label' => 'app.to.year',
                        'input' => 'array',
                        'required' => false,
                        'placeholder' => 'bitte auswählen',
                        'mapped' => false,
                    ])
            )
            ->add('submit', SubmitType::class, [
                'label' => 'Exportieren',
                'attr' => [
                    'class' => 'btn-conuti float-end'
                ]
            ])
        ;
    }

    private function getEmployees(): array
    {
        $employeeIds = $this->employeeRepository->getAllIds();

        $employees = [];
        foreach ($employeeIds as $employeeId) {
            $employees[$this->employeeRepository->getNameById($employeeId)[0]["firstName"]." ".$this->employeeRepository->getNameById($employeeId)[0]["name"]] = $employeeId;
        }

        return $employees;
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
