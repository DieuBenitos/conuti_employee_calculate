<?php

namespace App\Entity;

use App\Repository\IncentiveRateRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: IncentiveRateRepository::class)]
class IncentiveRate
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'incentiveRate')]
    private ?Employee $employee = null;

    #[ORM\Column]
    private ?float $incentiveRate = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $month = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $year = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $modificationDate = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $info = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmployee(): ?Employee
    {
        return $this->employee;
    }

    public function setEmployee(?Employee $employee): static
    {
        $this->employee = $employee;

        return $this;
    }

    public function getIncentiveRate(): ?float
    {
        return $this->incentiveRate;
    }

    public function setIncentiveRate(float $incentiveRate): static
    {
        $this->incentiveRate = $incentiveRate;

        return $this;
    }

    public function getMonth(): ?string
    {
        return $this->month;
    }

    public function setMonth(string $month): static
    {
        $this->month = $month;

        return $this;
    }

    public function getYear(): ?string
    {
        return $this->year;
    }

    public function setYear(string $year): static
    {
        $this->year = $year;

        return $this;
    }

    public function getModificationDate(): ?\DateTimeInterface
    {
        return $this->modificationDate;
    }

    public function setModificationDate(?\DateTimeInterface $modificationDate): static
    {
        $this->modificationDate = $modificationDate;

        return $this;
    }

    public function getInfo(): ?string
    {
        return $this->info;
    }

    public function setInfo(?string $info): static
    {
        $this->info = $info;

        return $this;
    }
}
