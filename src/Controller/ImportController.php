<?php

namespace App\Controller;

use App\Entity\Employee;
use App\Entity\HourlyRate;
use App\Entity\Hours;
use App\Entity\IncentiveRate;
use App\Form\UploadType;
use App\Repository\EmployeeRepository;
use App\Repository\HourlyRateRepository;
use App\Repository\HoursRepository;
use App\Repository\IncentiveRateRepository;
use App\Repository\RiffleRepository;
use App\Service\FileUploader;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

//#[IsGranted('ROLE_ADMIN')]
class ImportController extends AbstractController
{
    public function __construct(
        private readonly EmployeeRepository $employeeRepository,
        private readonly HourlyRateRepository $hourlyRateRepository,
        private readonly IncentiveRateRepository $incentiveRateRepository,
        private readonly HoursRepository $hoursRepository,
        private readonly RiffleRepository $riffleRepository,
    ) {}

    #[Route('/import', name: 'import_list')]
    public function listAction(
        Request $request,
    ): Response
    {
        return $this->render('import/list.html.twig');
    }

    #[Route('/import/employees', name: 'import_employees')]
    public function employees(
        Request $request,
        FileUploader $fileUploader,
    ): Response
    {
        $employee = new Employee();
        $form = $this->createForm(UploadType::class, $employee);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {

            /** @var UploadedFile $brochureFile */
            $excelFile = $form->get('import')->getData();

            if ($excelFile) {
                $excelFileName = $fileUploader->upload($excelFile);
                $fileFolder = __DIR__ . '/../../public/uploads/';
                $spreadsheet = IOFactory::load($fileFolder . $excelFileName);
                $row = $spreadsheet->getActiveSheet()->removeRow(1);
                $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

                foreach ($sheetData as $Row)
                {
                    $personalNumber = $Row['A'];
                    $name = $Row['B'];
                    $firstName = $Row['C'];
                    $hourlyRate = $Row['D'];
                    $incentiveRate = $Row['E'];
                    $targetSalary = $Row['F'];
                    $targetVariablePortion = $Row['G'];
                    $fixPortion = $Row['H'];

                    $employee = $this->employeeRepository->findOneBy(["employeeNumber" => $personalNumber]);

                    if (!$employee instanceof Employee) {
                        $employee = new Employee();

                        $employee->setName($name);
                        $employee->setFirstName($firstName);
                        $employee->setEmployeeNumber($personalNumber);
                        $employee->setEmail(lcfirst($firstName).".".lcfirst($name)."@conuti.de");

                        $employee->setTargetSalary($targetSalary);
                        $employee->setTargetVariablePortion($targetVariablePortion);
                        $employee->setFixPortion($fixPortion);
                        $this->employeeRepository->add($employee);

                        $hourlyRateObject = new HourlyRate();
                        $hourlyRateObject->setHourlyRate($hourlyRate);
                        $hourlyRateObject->setEmployee($employee);
                        $hourlyRateObject->setModificationDate(new \DateTime());
                        $this->hourlyRateRepository->add($hourlyRateObject);

                        $incentiveRateObject = new IncentiveRate();
                        $incentiveRateObject->setIncentiveRate($incentiveRate);
                        $incentiveRateObject->setEmployee($employee);
                        $incentiveRateObject->setModificationDate(new \DateTime());
                        $this->incentiveRateRepository->add($incentiveRateObject);

                        $hours = new Hours();

                        $employee->addIncentiveRate($incentiveRateObject);
                        $employee->addHourlyRate($hourlyRateObject);
                        $employee->addHour($hours);
                        $this->employeeRepository->add($employee);
                    }
                }
                return $this->redirectToRoute('import_list');
            }
        }
        return $this->render('import/employees.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @throws \JsonException
     */
    #[Route('/import/hours', name: 'import_hours')]
    public function hours(
        Request $request,
        FileUploader $fileUploader,
    ): Response
    {
        $hours = new Hours();
        $form = $this->createForm(UploadType::class, $hours);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            /** @var UploadedFile $brochureFile */
            $excelFile = $form->get('import')->getData();

            $month = $form->get('month')->getViewData();
            $year = $form->get('year')->getViewData()["year"];
            $checkIfImported = $form->get('checkIfImported')->getData();

            $hourThisMonth = $this->hoursRepository->findOneBy(["month" => $month, "year" => $year]);

            if($hourThisMonth instanceof Hours && !$checkIfImported) {
                $this->addFlash('warning', 'Stunden für diesen Monat bereits vorhanden!');
                return $this->redirectToRoute('import_hours', [
                    'month' => $month,
                    'year' => $year,
                ]);
            }

            if ($excelFile) {
                $excelFileName = $fileUploader->upload($excelFile);
                $fileFolder = __DIR__ . '/../../public/uploads/';  //choose the folder in which the uploaded file will be stored
                $spreadsheet = IOFactory::load($fileFolder . $excelFileName); // Here we are able to read from the excel file
                $row = $spreadsheet->getActiveSheet()->removeRow(1); // I added this to be able to remove the first file line
                $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true); // here, the read data is turned into an array

                foreach ($sheetData as $Row)
                {
                    $name = $Row['A']; // store the first_name on each iteration
                    $hour = $Row['B']; // store the last_name on each iteration
                    $note = $Row['C'];     // store the email on each iteration

                    $employee = $this->employeeRepository->findOneBy(['name' => explode(' ',$name)[1], 'firstName' => explode(' ',$name)[0]]);

                    if (!$employee) {
                        $firstName = explode(' ',$name)[0];
                        $name = explode(' ',$name)[1];
                        $employee = new Employee();
                        $employee->setName($name);
                        $employee->setFirstName($firstName);
                        $employee->setEmail(lcfirst($firstName).".".lcfirst($name)."@conuti.de");
                        $employee->setInfo($note);
                        $employee->setEmployeeNumber(count($this->employeeRepository->findAll()) + 1);
                        $this->employeeRepository->add($employee);
                        $this->addFlash('success', 'Neue Mitarbeiter angelegt!');
                    }

                    $riffle = $this->riffleRepository->findOneBy(['acquiredName' => $employee->getName(), 'acquiredFirstname' => $employee->getFirstName()]);
                    if ($riffle) {
                        $acquiredHoursCollection = $riffle->getAcquiredHoursCollection();
                        $i = 0;
                        foreach ($acquiredHoursCollection as $element) {
                            $i++;
                            if ($element["month"] === $month*1 && $element["year"] === $year) {
                                $acquiredHoursCollection[$i]["isAcquired"] = true;
                            } else {
                                $acquiredHoursCollection[$i]['month'] = $month*1;
                                $acquiredHoursCollection[$i]['year'] = $year;
                                $acquiredHoursCollection[$i]['isAcquired'] = true;
                            }
                        }
                        $riffle->setAcquiredHoursCollection($acquiredHoursCollection);
                        $riffle->setAcquiredHours(true);
                        $riffle->setMonth($month*1);
                        $riffle->setYear($year);
                        $this->riffleRepository->add($riffle);
                    }

                    $hours = $this->hoursRepository->findOneBy(['employee' => $employee, 'month' => $month *= 1, 'year' => $year]);
                    if (!$hours) {
                        $hours = new Hours();
                        $hours->setEmployee($employee);
                        $hours->setMonth($month);
                        $hours->setYear($year);
                    }
                    $hours->setVariableHours($hour);
                    $hours->setModificationDate(new \DateTime());
                    $this->hoursRepository->add($hours);
                }
                $this->addFlash('success', 'Zeiten Erfolgreich gespeichert!');
            }

            return $this->redirectToRoute('import_hours');
        }

        return $this->render('import/hours.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
