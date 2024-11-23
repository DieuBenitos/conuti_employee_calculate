<?php

namespace App\Entity;

use App\Repository\HoursRepository;
use DateTimeInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HoursRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Hours
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'hours')]
    private ?Employee$employee = null;

    #[ORM\Column(nullable: true)]
    private ?int $month = null;

    #[ORM\Column(nullable: true)]
    private ?int $year = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, updatable: 'true')]
    private ?\DateTimeInterface $modificationDate = null;

    #[ORM\Column(nullable: true)]
    private ?float $variableHours = null;

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

    public function getMonth(): ?string
    {
        return $this->month;
    }

    public function setMonth(?string $month): static
    {
        $this->month = $month;

        return $this;
    }

    public function getYear(): ?string
    {
        return $this->year;
    }

    public function setYear(?string $year): static
    {
        $this->year = $year;

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

    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function updateModificationDate() {
        $this->setModificationDate(new \DateTime());
    }

    public function getVariableHours(): ?float
    {
        return $this->variableHours;
    }

    public function setVariableHours(?float $variableHours): static
    {
        $this->variableHours = $variableHours;

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
