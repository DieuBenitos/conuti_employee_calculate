<?php

namespace App\Controller;

use App\Entity\Employee;
use App\Entity\HourlyRate;
use App\Entity\Hours;
use App\Entity\IncentiveRate;
use App\Form\EmployeeType;
use App\Repository\BenefitsRepository;
use App\Repository\BenefitsTypeRepository;
use App\Repository\BonusRepository;
use App\Repository\EmployeeRepository;
use App\Repository\GoodiesRepository;
use App\Repository\HourlyRateRepository;
use App\Repository\HoursRepository;
use App\Repository\IncentiveRateRepository;
use App\Repository\RiffleRepository;
use App\Repository\VariablePortionRepository;
use Doctrine\ORM\EntityNotFoundException;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

//#[IsGranted('ROLE_USER')]
class EmployeeController extends AbstractController
{
    public function __construct(
        private readonly EmployeeRepository $repository,
        private readonly HourlyRateRepository $hourlyRateRepository,
        private readonly HoursRepository $hoursRepository,
        private readonly IncentiveRateRepository $incentiveRateRepository,
        private readonly VariablePortionRepository $variablePortionRepository,
        private readonly BenefitsRepository $benefitsRepository,
        private readonly RiffleRepository $riffleRepository,
        private readonly GoodiesRepository $goodiesRepository,
        private readonly BonusRepository $bonusRepository,
        private readonly BenefitsTypeRepository $benefitsTypeRepository,

    ) {}

    #[Route('/employees', name: 'app_employee')]
    public function indexAction(): Response
    {
        $hasAccess = $this->isGranted('ROLE_ADMIN');
        $valuesArray = [];
        $employees = [];
        if ($hasAccess || !$this->getUser()) {
            $employees = $this->repository->findAll();
            foreach ($employees as $employee) {
                $valuesArray[$employee->getId()] = $this->getIndexData($employee)[$employee->getId()];
            }
        } else {
            $employee = $this->repository->findOneBy(['email' => $this->getUser()->getEmail()]);
            $employees[$employee->getId()] = $employee;
            $valuesArray = $this->getIndexData($employee);
        }

        return $this->render('employee/index.html.twig', [
            'employees' => $employees,
            'values' => $valuesArray
        ]);
    }

//    #[IsGranted('ROLE_ADMIN')]
    #[Route('/employee/create', name: 'app_employee_create')]
    public function createAction(Request $request): Response
    {
        $form = $this->createForm(EmployeeType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $employee = new Employee();
            $this->repository->setValues($employee, $data);
            $this->setRelationValues($data, $employee);
            $this->addFlash('success', 'Erfolgreich gespeichert!');

            return $this->redirectToRoute('app_employee_create');
        }

        return $this->render('employee/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @throws Exception
     */
//    #[IsGranted('ROLE_ADMIN')]
    #[Route('/employee/edit/{employeeId}', name: 'app_employee_edit')]
    public function editAction(Request $request, int $employeeId): Response
    {
        $employee = $this->repository->findOneById($employeeId);
        if (!$employee) {
            throw new EntityNotFoundException('Employee not found');
        }

        $monetaryBenefitType = $this->benefitsTypeRepository->findBy(["isMonetaryBenefit" => true]);

        $employeeValues = $this->getEmployeeValues($employee);
        $form = $this->createForm(EmployeeType::class, $employeeValues);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $benefitsTypes = $this->benefitsTypeRepository->findAll();
            $data = $form->getData();

            foreach($benefitsTypes as $benefitsType){
                if($benefitsType->isMonetaryBenefit()){
                    $data["benefitsMonetaryBenefit".$benefitsType->getId()] = $form->get('benefitsMonetaryBenefit'.$benefitsType->getId())->getViewData();
                    $data["benefitsPrivatePortion".$benefitsType->getId()] = $form->get('benefitsPrivatePortion'.$benefitsType->getId())->getViewData();
                }
            }
            $this->repository->setValues($employee, $data);
            $this->setRelationValues($data, $employee);
            $this->addFlash('success', 'Erfolgreich gespeichert!');

            return $this->redirectToRoute('app_employee_edit',[
                "employeeId" => $employeeId,
            ]);
        }

        return $this->render('employee/edit.html.twig', [
            'form' => $form->createView(),
            'monetaryBenefitType' => $monetaryBenefitType,
        ]);
    }

//    #[IsGranted('ROLE_ADMIN')]
    #[Route('/employee/delete/{employeeId}', name: 'app_employee_delete')]
    public function deleteEntity(int $employeeId): Response
    {
        $employee = $this->repository->findOneById($employeeId);
        $hours = $this->hoursRepository->findBy(['employee' => $employeeId],['id' => 'ASC']);
        $incentiveRates = $this->incentiveRateRepository->findBy(['employee' => $employee]);
        $hourlyRates = $this->hourlyRateRepository->findBy(['employee' => $employee]);

        foreach ($hours as $hour) {
            if ($hour instanceof Hours) {
                $this->hoursRepository->remove($hour);
            }
        }

        foreach ($incentiveRates as $incentiveRate) {
            if ($incentiveRate instanceof IncentiveRate) {
                $this->incentiveRateRepository->remove($incentiveRate);
            }
        }

        foreach ($hourlyRates as $hourlyRate) {
            if ($hourlyRate instanceof HourlyRate) {
                $this->hourlyRateRepository->remove($hourlyRate);
            }
        }

        $this->hoursRepository->flush();

        if ($employee instanceof Employee) {
            $this->repository->remove($employee);
            $this->repository->flush();
        }

        return $this->redirectToRoute('app_employee');
    }

    private function setRelationValues(array $data, Employee $employee): void
    {
        $this->handleRate(
            $data['hourlyRate'] ?? 0.0,
            $employee,
            $this->hourlyRateRepository,
            HourlyRate::class
        );

        $this->handleRate(
            $data['incentiveRate'] ?? 0.0,
            $employee,
            $this->incentiveRateRepository,
            IncentiveRate::class
        );

        $this->handleAssociations(
            $data['benefits'] ?? [],
            $employee,
            $this->benefitsRepository,
            'benefitsType',
            $data
        );

        $this->handleAssociations(
            $data['hours'] ?? [],
            $employee,
            $this->hoursRepository
        );

        $this->handleAssociations(
            $data['riffles'] ?? [],
            $employee,
            $this->riffleRepository
        );

        $this->handleAssociations(
            $data['goodies'] ?? [],
            $employee,
            $this->goodiesRepository
        );

        $this->handleAssociations(
            $data['bonuses'] ?? [],
            $employee,
            $this->bonusRepository
        );
    }

    private function handleRate(?float $rateData, Employee $employee, $repository, string $rateClass): void
    {
        if (!$rateData) {
            return;
        }

        $dateTime = new \DateTime('now');
        $currentMonth = (int)$dateTime->format('m');
        $currentYear = (int)$dateTime->format('Y');

        $rate = $repository->findOneBy([
            "employee" => $employee,
            "month" => $currentMonth,
            "year" => $currentYear
        ]);

        if (!$rate instanceof $rateClass) {
            $rate = new $rateClass();
            $rate->setMonth($currentMonth);
            $rate->setYear($currentYear);
        }

        $repository->setValues($rate, $rateData, $employee);
    }

    private function handleAssociations(
        array $data,
        Employee $employee,
        $repository,
        ?string $relationKey = null,
        ?array $extraData = null
    ): void {
        $existingItems = $repository->findBy(['employee' => $employee]);
        $selectedItems = [];

        if (!empty($data)) {
            $repository->setValues($data, $employee, $extraData ?? []);
            foreach ($data as $itemData) {
                $selectedItems[] = $relationKey
                    ? $repository->findOneBy(['employee' => $employee, $relationKey => $itemData])
                    : $itemData;
            }
        }

        foreach ($existingItems as $item) {
            if (!in_array($item, $selectedItems, true)) {
                $repository->remove($item);
            }
        }
    }

    private function getEmployeeValues(Employee $employee): array
    {
        $variablePortion = $this->variablePortionRepository->findOneBy(['employee' => $employee], ['payoutYear' => 'DESC', 'payoutMonth' => 'DESC']);
        $hours = $this->hoursRepository->findBy(['employee' => $employee], ['year' => 'DESC', 'month' => 'DESC']);
        $hourlyRate = $this->hourlyRateRepository->findOneBy(['employee' => $employee],['modificationDate' => 'DESC']);
        $incentiveRate = $this->incentiveRateRepository->findOneBy(['employee' => $employee],['modificationDate' => 'DESC']);

        return [
            'employee' => $employee,
            'hourlyRate' => $hourlyRate ? $hourlyRate->getHourlyRate() : '',
            'incentiveRate' => $incentiveRate ? $incentiveRate->getIncentiveRate() : '',
            'riffles' => $employee->getRiffles(),
            'goodies' => $employee->getGoodies(),
            'bonuses' => $employee->getBonuses(),
            'benefits' => $employee->getBenefits(),
            'payoutMonth' => $variablePortion ? $variablePortion->getPayoutMonth() . $variablePortion->getPayoutYear() : '',
            'variablePortion' => $variablePortion ? $variablePortion->getPayoutPortion() : '',
            'hours' => $hours,
        ];
    }

    private function getIndexData(Employee $employee): array
    {
        $dateTime = new \DateTime('now');

        $values = [];
        $incentiveRate = $this->incentiveRateRepository->findOneBy(["employee" => $employee], ["modificationDate" => "DESC"]);
        $values[$employee->getId()]['incentiveRate'] = $_ENV["INCENTIVE_RATE"];
        if ($incentiveRate instanceof IncentiveRate) {
            $values[$employee->getId()]['incentiveRate'] = $incentiveRate->getIncentiveRate();
        }

        $hourlyRate = $this->hourlyRateRepository->findOneBy(["employee" => $employee], ["modificationDate" => "DESC"]);
        $values[$employee->getId()]['hourlyRates'] = $_ENV["HOURLY_RATE"];
        if ($hourlyRate instanceof HourlyRate) {
            $values[$employee->getId()]['hourlyRates'] = $hourlyRate->getHourlyRate();
        }

        $variablePortion = $this->variablePortionRepository->findOneBy(["employee" => $employee, "payoutYear" => $dateTime->format('Y'), "payoutMonth" => $dateTime->format('m')*1]);
        $values[$employee->getId()]['variablePortions'] = $variablePortion ? $variablePortion->getPayoutPortion() : 0;
        $values[$employee->getId()]['benefits'] = $employee->getBenefits()->getValues();
        $values[$employee->getId()] ['goodies']= $employee->getGoodies()->getValues();
        $values[$employee->getId()] ['bonus']= $employee->getBonuses()->getValues();
        $values[$employee->getId()] ['riffles']= $employee->getRiffles()->getValues();

        return $values;
    }
}
