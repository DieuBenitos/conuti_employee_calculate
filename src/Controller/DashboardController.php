<?php

namespace App\Controller;

use App\Entity\Employee;
use App\Repository\EmployeeRepository;
use App\Repository\VariablePortionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

//#[IsGranted('ROLE_USER')]
class DashboardController extends AbstractController
{
    private int $index = 0;
    public function __construct(
        private readonly EmployeeRepository $employeeRepository,
        private readonly VariablePortionRepository $variablePortionRepository,
    )
    {}
    #[Route('/', name: 'app_dashboard')]
    public function dashboardAction(): Response
    {
        $hasAccess = $this->isGranted('ROLE_ADMIN');

        $chartData = [];
        $date = new \DateTime('now');
        $year = $date->format('Y');
        $month = $date->format('m');
        $average = $month * $_ENV["VARIABLE_AVERAGE"];

        if ($hasAccess || !$this->getUser()) {
            $employees = $this->employeeRepository->findAll();
            foreach ($employees as $employee) {
                $chartData[] = $this->getVariableData($employee, $year, $average)[$this->index];
                $this->index++;
            }
        } else {
            $employee = $this->employeeRepository->findOneBy(['email' => $this->getUser()->getEmail()]);
            $chartData = $this->getVariableData($employee, $year, $average);
        }

        return $this->render('dashboard.html.twig', [
            'chartData' => $chartData,
        ]);
    }


    private function getVariableData(Employee $employee, string $year, float $average): array
    {
        $totalVariable = 0;
        $variables = $this->variablePortionRepository->findPortionsByEmployeeAndYear($employee, $year, "ASC");
        $chartData[$this->index]["name"] = $employee->getFirstName() . " " . $employee->getName();
        foreach ($variables as $variable) {
            $totalVariable += $variable["portions"];
            $chartData[$this->index]["data"][] = $variable["portions"];
        }
        if ($totalVariable < $average) {
            $chartData[$this->index]["visible"] = false;
        }

        return $chartData;
    }
}
