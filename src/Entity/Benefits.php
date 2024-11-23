<?php

namespace App\Entity;

use App\Repository\BenefitsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BenefitsRepository::class)]
class Benefits
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $month = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $year = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $creationDate = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $modificationDate = null;

    #[ORM\ManyToOne(inversedBy: 'benefits')]
    private ?Employee $employee = null;

    #[ORM\ManyToOne(inversedBy: 'benefits')]
    private ?BenefitsType $benefitsType = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $monetaryBenefit = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $privatePortion = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $info = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getCreationDate(): ?\DateTimeInterface
    {
        return $this->creationDate;
    }

    public function setCreationDate(\DateTimeInterface $creationDate): static
    {
        $this->creationDate = $creationDate;

        return $this;
    }

    public function getModificationDate(): ?\DateTimeInterface
    {
        return $this->modificationDate;
    }

    public function setModificationDate(\DateTimeInterface $modificationDate): static
    {
        $this->modificationDate = $modificationDate;

        return $this;
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

    public function getBenefitsType(): ?BenefitsType
    {
        return $this->benefitsType;
    }

    public function setBenefitsType(?BenefitsType $benefitsType): static
    {
        $this->benefitsType = $benefitsType;

        return $this;
    }

    public function getMonetaryBenefit(): ?string
    {
        return $this->monetaryBenefit;
    }

    public function setMonetaryBenefit(string $monetaryBenefit): static
    {
        $this->monetaryBenefit = $monetaryBenefit;

        return $this;
    }

    public function getPrivatePortion(): ?string
    {
        return $this->privatePortion;
    }

    public function setPrivatePortion(string $privatePortion): static
    {
        $this->privatePortion = $privatePortion;

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
