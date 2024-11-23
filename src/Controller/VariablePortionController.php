<?php

namespace App\Controller;

use App\Entity\Employee;
use App\Entity\VariablePortion;
use App\Repository\EmployeeRepository;
use App\Repository\HoursRepository;
use App\Repository\VariablePortionRepository;
use App\Service\CalcVariableSalary;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

//#[IsGranted('ROLE_USER')]
class VariablePortionController extends AbstractController
{
    public function __construct(
        private readonly CalcVariableSalary $calcVariableSalary,
        private readonly VariablePortionRepository $variablePortionRepository,
        private readonly EmployeeRepository $employeeRepository,
        private readonly HoursRepository $hoursRepository,
    ) {}

    #[Route('/variables', name: 'app_variables_index')]
    public function indexAction(Request $request): Response
    {
        $hasAccess = $this->isGranted('ROLE_ADMIN');

        $years = $this->variablePortionRepository->getYears();
        $dataArray = [];

        if ($hasAccess || !$this->getUser()) {
            $employees = $this->employeeRepository->findAll();
            foreach ($employees as $employee) {
                $dataArray["employees"][$employee->getId()] = $this->getData($employee);
            }
        } else {
            $employee = $this->employeeRepository->findOneBy(['email' => $this->getUser()->getEmail()]);
            $dataArray["employees"][$employee->getId()] = $this->getData($employee);
        }

        $dataArray["years"] = [];
        foreach($years as $year){
            $dataArray["years"][] = $year["payoutYear"];
        }

        return $this->render('variable/index.html.twig', [
            "data" => $dataArray,
        ]);
    }
    #[Route('/getVariablePortions/{month}/{year}', name: 'get_current_variable_portions')]
    public function getCurrentVariablePortions(string $month, string $year): Response
    {
        $employees = $this->employeeRepository->findAll();

        foreach ($employees as $employee) {
            $values = $this->calcVariableSalary->calcVariableSalaryForEmployee($employee->getId(), $month, $year);
            $variablePortion = $this->variablePortionRepository->findOneBy(['employee' => $employee, 'payoutMonth' => $month*1, 'payoutYear' => $year]);
            if (!$variablePortion instanceof VariablePortion) {
                $variablePortion = new VariablePortion();
                $variablePortion->setEmployee($employee);
                $variablePortion->setCreatedAt(new \DateTime());
            }
            $variablePortion->setPortions($values["variables"]);
            $variablePortion->setFixVariablePortion($values["fixVariables"]);
            $variablePortion->setRifflePortion($values["riffles"]);
            $variablePortion->setGoodyPortion($values["goodies"]);
            $variablePortion->setBonusPortion($values["bonuses"]);
            $variablePortion->setPayoutPortion($values["payout"]);
            $variablePortion->setCalcIncentiveRate($values["incentiveRate"]);
            $variablePortion->setCalcBorderHourValue($values["borderHourValue"]);
            $variablePortion->setPayoutMonth($month*1);
            $variablePortion->setPayoutYear($year);
            $employee->addVariablePortion($variablePortion);
            $this->variablePortionRepository->add($variablePortion);
        }

        return new JsonResponse([],200);
    }

    #[Route('/getVariablePortions', name: 'get_variable_portions')]
    public function getVariablePortions(): Response
    {
        $employees = $this->employeeRepository->findAll();
        foreach ($employees as $employee) {
            $hours = $employee->getHours();
            foreach ($hours as $hour) {
                $values = $this->calcVariableSalary->calcVariableSalaryForEmployee($employee->getId(), $hour->getMonth(), $hour->getYear());
                $variablePortion = $this->variablePortionRepository->findOneBy(['employee' => $employee, 'payoutMonth' => $hour->getMonth()*1, 'payoutYear' => $hour->getYear()]);
                if (!$variablePortion instanceof VariablePortion) {
                    $variablePortion = new VariablePortion();
                    $variablePortion->setEmployee($employee);
                    $variablePortion->setCreatedAt(new \DateTime());
                }
                $variablePortion->setPortions($values["variables"]);
                $variablePortion->setFixVariablePortion($values["fixVariables"]);
                $variablePortion->setRifflePortion($values["riffles"]);
                $variablePortion->setGoodyPortion($values["goodies"]);
                $variablePortion->setBonusPortion($values["bonuses"]);
                $variablePortion->setPayoutPortion($values["payout"]);
                $variablePortion->setCalcIncentiveRate($values["incentiveRate"]);
                $variablePortion->setCalcBorderHourValue($values["borderHourValue"]);
                $variablePortion->setPayoutMonth($hour->getMonth()*1);
                $variablePortion->setPayoutYear($hour->getYear());
                $employee->addVariablePortion($variablePortion);
                $this->variablePortionRepository->add($variablePortion);
            }
        }

        return $this->redirectToRoute('app_variables_index',[]);
    }

    private function getData(Employee $employee): array
    {
            $variable = [];
            $hourArray = [];
            $variablePortions = $employee->getVariablePortion();
            $hours = $this->hoursRepository->findBy(['employee' => $employee],['year' => 'ASC', 'month' => 'ASC']);

            foreach ($variablePortions as $variablePortion) {
                $variable[$variablePortion->getId()]["month"] = $variablePortion->getPayoutMonth();
                $variable[$variablePortion->getId()]["year"] = $variablePortion->getPayoutYear();
                $variablePortions = $variablePortion->getPortions();
                $fixVariablePortion = $variablePortion->getFixVariablePortion();
                $payoutPortion = $variablePortion->getPayoutPortion();
                $goodyPortion = $variablePortion->getGoodyPortion();
                $bonusPortion = $variablePortion->getBonusPortion();
                $rifflePortion = $variablePortion->getRifflePortion();
                $incentiveRate = $variablePortion->getCalcIncentiveRate();
                $borderHourValue = $variablePortion->getCalcBorderHourValue();
                $variable[$variablePortion->getId()]["variable"] = $variablePortions;
                $variable[$variablePortion->getId()]["payout"] = $payoutPortion;
                $variable[$variablePortion->getId()]["fixVariable"] = $fixVariablePortion;
                $variable[$variablePortion->getId()]["goody"] = $goodyPortion;
                $variable[$variablePortion->getId()]["bonus"] = $bonusPortion;
                $variable[$variablePortion->getId()]["riffle"] = $rifflePortion;
                $variable[$variablePortion->getId()]["incentiveRate"] = $incentiveRate;
                $variable[$variablePortion->getId()]["borderHourValue"] = $borderHourValue;
            }

            foreach ($hours as $hour) {
                $hourArray[$hour->getId()]["month"] = $hour->getMonth();
                $hourArray[$hour->getId()]["year"] = $hour->getYear();
                $hourArray[$hour->getId()]["hour"] = $hour->getVariableHours();
            }

            return [
                "employeeNumber" => $employee->getEmployeeNumber(). ". " . $employee->getFirstName() . " " . $employee->getName(),
                "variables" => $variable,
                "hours" => $hourArray,
            ];

    }
}
