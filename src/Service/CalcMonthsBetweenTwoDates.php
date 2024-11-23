<?php

namespace App\Service;

use DateInterval;
use DatePeriod;
use DateTime;

class CalcMonthsBetweenTwoDates
{
    public function calcMonths($fromYear, $fromMonth, $toYear, $toMonth): array
    {
        $begin = new DateTime();
        $begin->setDate((int) $fromYear, (int) $fromMonth,1);
        $end = new DateTime();
        $end->setDate((int) $toYear, (int) $toMonth,1);
        $end = $end->modify( '+1 month' );

        $interval = DateInterval::createFromDateString('1 month');

        $period = new DatePeriod($begin, $interval, $end);

        $months = [];
        foreach($period as $dt) {
            $months[] = [
                "month" => $dt->format('m'),
                "year" => $dt->format('Y'),
            ];
        }

        return $months;
    }
}
