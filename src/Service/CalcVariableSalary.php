<?php

namespace App\Service;

use App\Entity\Employee;
use App\Repository\BonusRepository;
use App\Repository\EmployeeRepository;
use App\Repository\GoodiesRepository;
use App\Repository\HourlyRateRepository;
use App\Repository\HoursRepository;
use App\Repository\IncentiveRateRepository;
use App\Repository\RiffleRepository;
use DateTime;

class CalcVariableSalary
{
    public function __construct(
        private readonly EmployeeRepository $employeeRepository,
        private readonly HoursRepository $hoursRepository,
        private readonly HourlyRateRepository $hourlyRateRepository,
        private readonly IncentiveRateRepository $incentiveRateRepository,
        private readonly RiffleRepository $riffleRepository,
        private readonly GoodiesRepository $goodiesRepository,
        private readonly BonusRepository $bonusRepository,
    ) {}
    public function calcVariableSalaryForEmployee(
        int $employeeId,
        string $selectedMonth,
        string $selectedYear,
    ): array {

        $borderHours = $this->hourlyRateRepository->findBy(['employee' => $employeeId],['year' => 'ASC', 'month' => 'ASC']);
        $incentiveRates = $this->incentiveRateRepository->findBy(['employee' => $employeeId],['year' => 'ASC', 'month' => 'ASC']);
        $riffles = $this->riffleRepository->findBy(['employee' => $employeeId, 'isAcquiredHours' => true, 'month' => (int) $selectedMonth, 'year' => (int) $selectedYear]);
        $goodies = $this->goodiesRepository->findBy(['employee' => $employeeId]);
        $bonuses = $this->bonusRepository->findBy(['employee' => $employeeId]);
        $employee = $this->employeeRepository->findOneBy(['id' => $employeeId]);

        if($employee instanceof Employee) {
            $incentiveRateValue = $_ENV["INCENTIVE_RATE"];
            $borderHourValue = $_ENV["HOURLY_RATE"];

            foreach ($borderHours as $borderHour) {
                if ($borderHour->getMonth() <= $selectedMonth && $borderHour->getYear() <= $selectedYear) {
                    $borderHourValue = $borderHour->getHourlyRate();
                }
            }

            foreach ($incentiveRates as $incentiveRate) {
                if ($incentiveRate->getMonth() <= $selectedMonth && $incentiveRate->getYear() <= $selectedYear) {
                    $incentiveRateValue = $incentiveRate->getIncentiveRate();
                }
            }

            $date = new DateTime();
            $date->setDate((int) $selectedYear, (int) $selectedMonth,1);


            $variableToPay = $this->getVariableToPay($date, $employeeId, $borderHourValue, $incentiveRateValue);
            $fixVariableToPay = $this->getFixVariableToPay($employee, $borderHourValue, $incentiveRateValue, $selectedMonth, $selectedYear);
            $riffleToPay = $this->getRiffleToPay($riffles, $selectedMonth, $selectedYear);
            $bonusesToPay = $this->getBonusesToPay($bonuses);

            if($fixVariableToPay > 0) {
                $toPay = $fixVariableToPay;
            } else {
                $toPay = $variableToPay;
            }
            $goodiesToPay = $this->getGoodiesToPay($goodies, $selectedMonth, $selectedYear, $toPay);
            $toPayout = $this->getPayoutAfterGoodies($goodies,$selectedMonth, $selectedYear, $date, $toPay);

            return [
                "variables" => $variableToPay,
                "fixVariables" => $fixVariableToPay,
                "riffles" => $riffleToPay,
                "goodies" => $goodiesToPay,
                "bonuses" => $bonusesToPay,
                "payout" => $toPayout,
                "incentiveRate" => $incentiveRateValue,
                "borderHourValue" => $borderHourValue,
            ];
        }
        return [];
    }

    private function getVariableToPay(DateTime $date, int $employeeId, float $borderHourValue, float $incentiveRateValue): float {

        $neededTimes = [];
        for ($i = 0; $i < 3; $i++) {
            $neededTimes[$date->sub(new \DateInterval('P1M'))->format("m")] = $date->format("Y");
        }

        $variable = 0;
        foreach ($neededTimes as $month => $year) {
            $hours = $this->hoursRepository->findOneBy(['employee' => $employeeId, 'month' => $month *= 1, 'year' => $year]);
            if ($hours) {
                $variable += $hours->getVariableHours();
            }
        }
        $variableValue = ($variable/3) - $borderHourValue;
        return ($variableValue * $incentiveRateValue) > 0 ? ($variableValue * $incentiveRateValue) : 0.0;
    }

    private function getFixVariableToPay(Employee $employee, float $borderHourValue, float $incentiveRateValue, int $selectedMonth, int $selectedYear): float {
        $fixVariableValue = 0.0;
        $fixVariableEntry = $employee->getFixVariableEntry();

        if (
            $fixVariableEntry instanceof \DateTimeInterface &&
            $fixVariableEntry->format('m')*1 <= $selectedMonth &&
            $fixVariableEntry->format('Y')*1 <= $selectedYear &&
            $employee->getFixVariablePortion() > 0
            )
        {
            $fixVariable = $employee->getFixVariablePortion();
            $fixVariableValue = $fixVariable - $borderHourValue;
        }
        return ($fixVariableValue * $incentiveRateValue) > 0 ? ($fixVariableValue * $incentiveRateValue) : 0.0;
    }

    private function getRiffleToPay(array $riffles, int $selectedMonth, int $selectedYear): float
    {
        $rifflesToPay = 0.0;
        if (!empty($riffles)) {
            foreach($riffles as $riffle){
                $acquiredHours = $riffle->getAcquiredHoursCollection();
                $entry = $riffle->getAcquiredEntry();
                if (
                    $entry instanceof \DateTimeInterface &&
                    $entry->format('m')*1 <= $selectedMonth &&
                    $entry->format('Y')*1 <= $selectedYear
                ) {
                    foreach ($acquiredHours as $acquiredHour) {
                        if (
                            $acquiredHour["month"] === $selectedMonth &&
                            $acquiredHour["year"] === $selectedYear &&
                            $acquiredHour["isAcquired"] === true
                        ) {
                            $rifflesToPay += $riffle->getAmount();
                        }
                    }
                }
            }
        }
        return $rifflesToPay;
    }

    private function getGoodiesToPay(array $goodies, int $selectedMonth, int $selectedYear, float $variable): float
    {
        $amount = 0.0;
        if (!empty($goodies)) {
            foreach($goodies as $goody){
                $amortizations = $goody->getAmortization();
                foreach ($amortizations as $amortization) {
                    if ($amortization["month"]*1 === $selectedMonth && $amortization["year"]*1 === $selectedYear) {
                        $amount += $goody->getPartialAmounts();
                        if ($amount > $variable) {
                            $amount -= $amount;
                            break;
                        }
                    }
                }
            }

        }
        return $amount;
    }

    private function getBonusesToPay(array $bonuses): float
    {
        $bonusToPay = 0.0;
        if (!empty($bonuses)) {
            foreach($bonuses as $bonus){
                if ($bonus->isAdd()) {
                    $bonusToPay += $bonus->getAmount();
                }
                if ($bonus->isSubtract()) {
                    $bonusToPay -= $bonus->getAmount();
                }
            }
        }
        return $bonusToPay;
    }

    private function getPayoutAfterGoodies(array $goodies, int $selectedMonth, int $selectedYear, DateTime $date, float $toPay): float
    {
        if (!empty($goodies)) {
            foreach($goodies as $goody){
                $amortizations = $goody->getAmortization();
                $i = 0;
                foreach ($amortizations as $amortization) {
                    $i++;
                    if ($amortization["month"]*1 === $selectedMonth && $amortization["year"]*1 === $selectedYear) {
                        $amount = $goody->getPartialAmounts();
                        $toPay -= $amount;
                        $goody->setCharged(false);
                        if ($toPay < 0) {
                            $toPay += $amount;
                            if ($amortization["charged"] === "") {
                                $amortizations[$i]["charged"] = "false";
                                $lastMonth = $amortizations[count($amortizations)]["month"];
                                $lastYear = $amortizations[count($amortizations)]["year"];
                                $date->setDate((int) $lastYear, (int) $lastMonth,1);
                                $amortizations[count($amortizations)+1]["month"] = $date->add(new \DateInterval('P1M'))->format("m");
                                $amortizations[count($amortizations)]["year"] = $date->format("Y");
                                $amortizations[count($amortizations)]["amount"] = $amount;
                                $amortizations[count($amortizations)]["charged"] = "";
                                $goody->setDivider(count($amortizations));
                            }
                            break;
                        }
                        $goody->setCharged(true);
                        $amortizations[$i]["charged"] = "true";
                        if ($i === count($amortizations)){
                            $goody->setInfo("Ist abbezahlt!");
                            $amortizations[$i]["charged"] = "finish";
                        }
                    }
                }
                $goody->setAmortization($amortizations);
                $this->goodiesRepository->add($goody);
            }
        }
        return $toPay;
    }
}
