<?php

namespace App\Entity;

use App\Repository\RiffleRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RiffleRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Riffle
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?float $amount = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $month = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $year = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $modificationDate = null;

    #[ORM\Column(length: 255)]
    private ?string $acquiredName = null;

    #[ORM\Column(length: 255)]
    private ?string $acquiredFirstname = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $acquiredEntry = null;

    #[ORM\ManyToOne(inversedBy: 'riffles')]
    private ?Employee $employee = null;

    #[ORM\Column(nullable: true)]
    private ?bool $isAcquiredHours = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $info = null;

    #[ORM\Column(nullable: true)]
    private ?array $acquiredHoursCollection = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): static
    {
        $this->amount = $amount;

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

    public function getAcquiredName(): ?string
    {
        return $this->acquiredName;
    }

    public function setAcquiredName(string $acquiredName): static
    {
        $this->acquiredName = $acquiredName;

        return $this;
    }

    public function getAcquiredFirstname(): ?string
    {
        return $this->acquiredFirstname;
    }

    public function setAcquiredFirstname(string $acquiredFirstname): static
    {
        $this->acquiredFirstname = $acquiredFirstname;

        return $this;
    }

    public function getAcquiredEntry(): ?\DateTimeInterface
    {
        return $this->acquiredEntry;
    }

    public function setAcquiredEntry(\DateTimeInterface $acquiredEntry): static
    {
        $this->acquiredEntry = $acquiredEntry;

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

    public function isAcquiredHours(): ?bool
    {
        return $this->isAcquiredHours;
    }

    public function setAcquiredHours(bool $isAcquiredHours): static
    {
        $this->isAcquiredHours = $isAcquiredHours;

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

    public function getAcquiredHoursCollection(): ?array
    {
        return $this->acquiredHoursCollection;
    }

    public function setAcquiredHoursCollection(?array $acquiredHoursCollection): static
    {
        $this->acquiredHoursCollection = $acquiredHoursCollection;

        return $this;
    }
}
