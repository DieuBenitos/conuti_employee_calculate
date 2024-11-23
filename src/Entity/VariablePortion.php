<?php

namespace App\Entity;

use App\Repository\VariablePortionRepository;
use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VariablePortionRepository::class)]
class VariablePortion
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'variablePortion')]
    private ?Employee $employee = null;

    #[ORM\Column]
    private ?float $portions = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $payoutMonth = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $payoutYear = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?float $fixVariablePortion = null;

    #[ORM\Column(nullable: true)]
    private ?float $goodyPortion = null;

    #[ORM\Column(nullable: true)]
    private ?float $bonusPortion = null;

    #[ORM\Column(nullable: true)]
    private ?float $rifflePortion = null;

    #[ORM\Column(nullable: true)]
    private ?float $payoutPortion = null;

    #[ORM\Column(nullable: true)]
    private ?float $calcIncentiveRate = null;

    #[ORM\Column(nullable: true)]
    private ?float $calcBorderHourValue = null;

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

    public function getPortions(): ?float
    {
        return $this->portions;
    }

    public function setPortions(float $portions): static
    {
        $this->portions = $portions;

        return $this;
    }

    public function getPayoutMonth(): ?string
    {
        return $this->payoutMonth;
    }

    public function setPayoutMonth(?string $payoutMonth): static
    {
        $this->payoutMonth = $payoutMonth;

        return $this;
    }

    public function getPayoutYear(): ?string
    {
        return $this->payoutYear;
    }

    public function setPayoutYear(?string $payoutYear): static
    {
        $this->payoutYear = $payoutYear;

        return $this;
    }

    public function getCreatedAt(): ?DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeInterface $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getFixVariablePortion(): ?float
    {
        return $this->fixVariablePortion;
    }

    public function setFixVariablePortion(?float $fixVariablePortion): static
    {
        $this->fixVariablePortion = $fixVariablePortion;

        return $this;
    }

    public function getGoodyPortion(): ?float
    {
        return $this->goodyPortion;
    }

    public function setGoodyPortion(?float $goodyPortion): static
    {
        $this->goodyPortion = $goodyPortion;

        return $this;
    }

    public function getRifflePortion(): ?float
    {
        return $this->rifflePortion;
    }

    public function setRifflePortion(?float $rifflePortion): static
    {
        $this->rifflePortion = $rifflePortion;

        return $this;
    }

    public function getBonusPortion(): ?float
    {
        return $this->bonusPortion;
    }

    public function setBonusPortion(?float $bonusPortion): static
    {
        $this->bonusPortion = $bonusPortion;

        return $this;
    }

    public function getPayoutPortion(): ?float
    {
        return $this->payoutPortion;
    }

    public function setPayoutPortion(?float $payoutPortion): static
    {
        $this->payoutPortion = $payoutPortion;

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

    public function getCalcIncentiveRate(): ?float
    {
        return $this->calcIncentiveRate;
    }

    public function setCalcIncentiveRate(?float $calcIncentiveRate): static
    {
        $this->calcIncentiveRate = $calcIncentiveRate;

        return $this;
    }

    public function getCalcBorderHourValue(): ?float
    {
        return $this->calcBorderHourValue;
    }

    public function setCalcBorderHourValue(?float $calcBorderHourValue): static
    {
        $this->calcBorderHourValue = $calcBorderHourValue;

        return $this;
    }
}
