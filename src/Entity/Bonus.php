<?php

namespace App\Entity;

use App\Repository\BonusRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BonusRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Bonus
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
    private ?\DateTimeInterface $modificationDate = null;

    #[ORM\Column]
    private ?float $amount = null;

    #[ORM\Column]
    private ?float $percent = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column]
    private ?bool $isAdd = null;

    #[ORM\Column]
    private ?bool $isSubtract = null;

    #[ORM\ManyToOne(inversedBy: 'bonuses')]
    private ?Employee $employee = null;

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

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): static
    {
        $this->amount = $amount;

        return $this;
    }

    public function getPercent(): ?float
    {
        return $this->percent;
    }

    public function setPercent(float $percent): static
    {
        $this->percent = $percent;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function isAdd(): ?bool
    {
        return $this->isAdd;
    }

    public function setAdd(bool $isAdd): static
    {
        $this->isAdd = $isAdd;

        return $this;
    }

    public function isSubtract(): ?bool
    {
        return $this->isSubtract;
    }

    public function setSubtract(bool $isSubtract): static
    {
        $this->isSubtract = $isSubtract;

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
