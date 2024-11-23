<?php

namespace App\Twig;

use DateTime;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class VariableHourExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('variableMonth', [$this, 'getMonth']),
            new TwigFunction('variableYear', [$this, 'getYear']),
        ];
    }

    public function getMonth($month, $year, $loop): int
    {
        $date = new DateTime();
        $date->setDate((int) $year, (int) $month,1);

        $calcMonth = 0;
        for ($i = 1; $i <= $loop; $i++){
            $date->sub(new \DateInterval('P1M'));
            $calcMonth = $date->format("m");
        }
        return $calcMonth;
    }

    public function getYear($month, $year, $loop): int
    {
        $date = new DateTime();
        $date->setDate((int) $year, (int) $month,1);

        $calcYear = 0;
        for ($i = 1; $i <= $loop; $i++){
            $date->sub(new \DateInterval('P1M'));
            $calcYear = $date->format("Y");
        }

        return $calcYear;
    }
}
