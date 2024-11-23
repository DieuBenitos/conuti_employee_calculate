<?php

namespace App\Controller;

use App\Entity\Employee;
use App\Entity\VariablePortion;
use App\Form\ConfiguratorType;
use App\Form\ExportType;
use App\Repository\EmployeeRepository;
use App\Repository\VariablePortionRepository;
use App\Service\CalcMonthsBetweenTwoDates;
use Doctrine\ORM\EntityManagerInterface;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Writer\Csv;
use PhpOffice\PhpSpreadsheet\Writer\Ods;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

//#[IsGranted('ROLE_USER')]
class ExportController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly EmployeeRepository $employeeRepository,
        private readonly VariablePortionRepository $variablePortionRepository,
        private readonly CalcMonthsBetweenTwoDates $calcMonthsBetweenTwoDates,
    ) {}

    #[Route('/export', name: 'export_list')]
    public function exportAction(Request $request): Response
    {
        $form = $this->createForm(ExportType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $format = $data['format'];
            $filename = 'Mitarbeiter.'.$format;

            $spreadsheet = $this->createAllEmployeesSpreadsheet();

            switch ($format) {
                case 'ods':
                    $contentType = 'application/vnd.oasis.opendocument.spreadsheet';
                    $writer = new Ods($spreadsheet);
                    break;
                case 'xlsx':
                    $contentType = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
                    $writer = new Xlsx($spreadsheet);
                    break;
                case 'csv':
                    $contentType = 'text/csv';
                    $writer = new Csv($spreadsheet);
                    break;
                default:
                    return $this->render('export/list.html.twig', [
                        'form' => $form->createView(),
                    ]);
            }

            $response = new StreamedResponse();
            $response->headers->set('Content-Type', $contentType);
            $response->headers->set('Content-Disposition', 'attachment;filename="'.$filename.'"');
            $response->setPrivate();
            $response->headers->addCacheControlDirective('no-cache', true);
            $response->headers->addCacheControlDirective('must-revalidate', true);
            $response->setCallback(function() use ($writer) {
                $writer->save('php://output');
            });

            return $response;
        }

        return $this->render('export/list.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/export/configurator', name: 'export_configurator')]
    public function configuratorAction(Request $request): Response
    {
        $hasAccess = $this->isGranted('ROLE_ADMIN');
        $form = $this->createForm(ConfiguratorType::class, ["hasAccess" => $hasAccess]);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $filename = 'Variable.xlsx';
            $spreadsheet = $this->createConfiguredSpreadsheet($form->get('dateGroup'));

            if ($spreadsheet->getActiveSheet()->getHighestRow() === 1) {
                $this->addFlash('warning', 'Es konnten keine Variablen gefunden werden!');
                return $this->redirectToRoute('export_configurator');
            }

            $writer = new Xlsx($spreadsheet);

            $response = new StreamedResponse();
            $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            $response->headers->set('Content-Disposition', 'attachment;filename="'.$filename.'"');
            $response->setPrivate();
            $response->headers->addCacheControlDirective('no-cache', true);
            $response->headers->addCacheControlDirective('must-revalidate', true);
            $response->setCallback(function() use ($writer) {
                $writer->save('php://output');
            });

            return $response;

        }
        return $this->render('export/configurator.html.twig', [
            'form' => $form
        ]);
    }

    private function createAllEmployeesSpreadsheet(): Spreadsheet
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $employeeMetaData = $this->entityManager->getClassMetadata(Employee::class);
        $columnNames = $employeeMetaData->getFieldNames();
        $columnNames[] = "Benefits";
        $columnNames[] = "Goodies";
        $columnNames[] = "Riffles";
        $columnNames[] = "Bonuses";

        $columnLetter = 'A';
        foreach ($columnNames as $columnName) {
            $sheet->setCellValue($columnLetter.'1', $columnName);
            $sheet->getColumnDimension($columnLetter)->setAutoSize(true);
            $columnLetter++;
        }

        $employees = $this->employeeRepository->findAll();

        $additionalValues = [];
        foreach($employees as $employee) {
            $additionalValues[$employee->getId()]["id"] = $employee->getEmployeeNumber();
            $benefits = $employee->getBenefits();
            $additionalValues[$employee->getId()]["benefits"] = [];
            foreach ($benefits as $benefit) {
                $monetaryBenefit = $benefit->getMonetaryBenefit() ? "GV: " .$benefit->getMonetaryBenefit() : "";
                $privatePortion = $benefit->getPrivatePortion() ? "PA: " . $benefit->getPrivatePortion() : "";
                $additionalValues[$employee->getId()]["benefits"][] = $benefit->getBenefitsType()->getName() . $monetaryBenefit . $privatePortion;
            }
            $goodies = $employee->getGoodies();
            $additionalValues[$employee->getId()]["goody"] = [];
            foreach($goodies as $goody){
                $additionalValues[$employee->getId()]["goody"][] = $goody->getDesignation() . " - " . $goody->getTotalAmount();
            }
            $riffles = $employee->getRiffles();
            $additionalValues[$employee->getId()]["riffle"] = [];
            foreach($riffles as $riffle){
                $additionalValues[$employee->getId()]["riffle"][] = $riffle->getAcquiredFirstname() . " " . $riffle->getAcquiredName() . " " . $riffle->getAmount();
            }
            $bonuses = $employee->getBonuses();
            $additionalValues[$employee->getId()]["bonus"] = [];
            foreach($bonuses as $bonus){
                $additionalValues[$employee->getId()]["bonus"][] = $bonus->isAdd() ? "Positiv: " . $bonus->getAmount() : "Negativ: " . $bonus->getAmount();
            }
        }

        // Add data for each column
        $columnValues = $this->employeeRepository->createQueryBuilder('e')->getQuery()->getArrayResult();

        $i = 2; // Beginning row for active sheet
        foreach ($columnValues as $columnValue) {
            $columnLetter = 'A';
            foreach ($columnValue as $value) {
                $sheet->setCellValue($columnLetter.$i, $value);
                $columnLetter++;
            }
            if(isset($additionalValues[$columnValue["id"]])){
                $sheet->setCellValue($columnLetter.$i, implode(", ", $additionalValues[$columnValue["id"]]["benefits"]));
                $columnLetter++;
                $sheet->setCellValue($columnLetter.$i, implode(", ", $additionalValues[$columnValue["id"]]["goody"]));
                $columnLetter++;
                $sheet->setCellValue($columnLetter.$i, implode(", ", $additionalValues[$columnValue["id"]]["riffle"]));
                $columnLetter++;
                $sheet->setCellValue($columnLetter.$i, implode(", ", $additionalValues[$columnValue["id"]]["bonus"]));
            }
            $i++;
        }

        return $spreadsheet;
    }

    private function createConfiguredSpreadsheet(Form $form): Spreadsheet
    {
        $data = $form->getData();

        if(isset($data["employees"])){
            $employeeIds = $data["employees"];
        } else {
            $employee = $this->employeeRepository->findOneBy(['email' => $this->getUser()->getEmail()]);
            $employeeIds[] = $employee->getId();
        }

        $date = new \DateTime();
        $fromMonths = $data["fromMonths"];
        $toMonths = $data["toMonths"];
        $toYears = $form->get('toYears')->getViewData()["year"];
        $fromYears = $form->get('fromYears')->getViewData()["year"];

        if (!$toYears) {
            $toYears = $date->format('Y');
        }

        if (!$toMonths) {
            $toMonths = $date->format('m')*1;
        }

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $months = $this->calcMonthsBetweenTwoDates->calcMonths($fromYears, $fromMonths, $toYears, $toMonths);
        $employeeMetaData = $this->entityManager->getClassMetadata(Employee::class);
        $columnNames = [
            $employeeMetaData->getFieldName('employeeNumber'),
            $employeeMetaData->getFieldName('name'),
            $employeeMetaData->getFieldName('firstName'),
        ];

        $columnValues = [];
        foreach ($months as $month) {
            $columnNames[] = $month["month"] . "-" . $month["year"];
            $columnNames[] = $month["month"] . "-" . $month["year"] . " - variable";
            $columnNames[] = $month["month"] . "-" . $month["year"] . " - fixVariable";
            $columnNames[] = $month["month"] . "-" . $month["year"] . " - Goody";
            $columnNames[] = $month["month"] . "-" . $month["year"] . " - Riffle";
            $columnNames[] = $month["month"] . "-" . $month["year"] . " - Bonus";

            foreach($employeeIds as $employeeId){
                $portion = $this->variablePortionRepository->findByEmployeeIdsMonthsAndYears($employeeId, (int)$month["month"], (int)$month["year"]);
                $columnValues[$employeeId]["fixVariable"][] = $portion ? $portion->getFixVariablePortion(): 0;
                $columnValues[$employeeId]["variable"][] = $portion ? $portion->getPortions(): 0;
                $columnValues[$employeeId]["payout"][] = $portion ? $portion->getPayoutPortion(): 0;
                $columnValues[$employeeId]["bonus"][] = $portion ? $portion->getBonusPortion(): 0;
                $columnValues[$employeeId]["goody"][] = $portion ? $portion->getGoodyPortion(): 0;
                $columnValues[$employeeId]["riffle"][] = $portion ? $portion->getRifflePortion(): 0;
            }
        }


        $columnLetter = 'A';
        foreach ($columnNames as $columnName) {
            $sheet->setCellValue($columnLetter.'1', $columnName);
            $sheet->getColumnDimension($columnLetter)->setAutoSize(true);
            $columnLetter++;
        }

        $i = 2; // Beginning row for active sheet
        foreach ($columnValues as $employeeId => $variables) {
            $employee = $this->employeeRepository->findOneBy(['id' => $employeeId]);
            if ($employee instanceof Employee) {
                $sheet->setCellValue('A'.$i, $employee->getEmployeeNumber());
                $sheet->setCellValue('B'.$i, $employee->getName());
                $sheet->setCellValue('C'.$i, $employee->getFirstName());
                $j = 4;
                foreach($variables["payout"] as $variable){
                    $sheet->setCellValue([$j,$i], $variable);
                    $j += 6;
                }
                $j = 5;
                foreach($variables["variable"] as $variable){
                    $sheet->setCellValue([$j,$i], $variable);
                    $j += 6;
                }
                $j = 6;
                foreach($variables["fixVariable"] as $variable){
                    $sheet->setCellValue([$j,$i], $variable);
                    $j += 6;
                }
                $j = 7;
                foreach($variables["goody"] as $variable){
                    $sheet->setCellValue([$j,$i], $variable);
                    $j += 6;
                }
                $j = 8;
                foreach($variables["riffle"] as $variable){
                    $sheet->setCellValue([$j,$i], $variable);
                    $j += 6;
                }
                $j = 9;
                foreach($variables["bonus"] as $variable){
                    $sheet->setCellValue([$j,$i], $variable);
                    $j += 6;
                }
            }
            $i++;
        }

        return $spreadsheet;
    }
}
