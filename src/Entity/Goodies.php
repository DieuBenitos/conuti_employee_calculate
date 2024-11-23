<?php

namespace App\Entity;

use App\Repository\GoodiesRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GoodiesRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Goodies
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $designation = null;

    #[ORM\Column]
    private ?float $totalAmount = null;

    #[ORM\Column]
    private ?int $divider = null;

    #[ORM\Column]
    private ?int $month = null;

    #[ORM\Column(length: 255)]
    private ?string $year = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $modificationDate = null;

    #[ORM\Column]
    private ?bool $charged = null;

    #[ORM\Column]
    private ?bool $changed = null;

    #[ORM\Column]
    private ?float $partialAmounts = null;

    #[ORM\ManyToOne(inversedBy: 'goodies')]
    private ?Employee $employee = null;

    #[ORM\Column(nullable: true)]
    private ?array $amortization = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $info = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDesignation(): ?string
    {
        return $this->designation;
    }

    public function setDesignation(string $designation): static
    {
        $this->designation = $designation;

        return $this;
    }

    public function getTotalAmount(): ?float
    {
        return $this->totalAmount;
    }

    public function setTotalAmount(float $totalAmount): static
    {
        $this->totalAmount = $totalAmount;

        return $this;
    }

    public function getDivider(): ?int
    {
        return $this->divider;
    }

    public function setDivider(int $divider): static
    {
        $this->divider = $divider;

        return $this;
    }

    public function getMonth(): ?int
    {
        return $this->month;
    }

    public function setMonth(int $month): static
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

    public function isCharged(): ?bool
    {
        return $this->charged;
    }

    public function setCharged(bool $charged): static
    {
        $this->charged = $charged;

        return $this;
    }

    public function isChanged(): ?bool
    {
        return $this->changed;
    }

    public function setChanged(bool $changed): static
    {
        $this->changed = $changed;

        return $this;
    }

    public function getPartialAmounts(): ?float
    {
        return $this->partialAmounts;
    }

    public function setPartialAmounts(float $partialAmounts): static
    {
        $this->partialAmounts = $partialAmounts;

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

    public function getAmortization(): ?array
    {
        return $this->amortization;
    }

    public function setAmortization(?array $amortization): static
    {
        $this->amortization = $amortization;

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
