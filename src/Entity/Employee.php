<?php

namespace App\Entity;

use App\Repository\EmployeeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: EmployeeRepository::class)]
class Employee
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Length(max: 20)]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Length(max: 20)]
    private ?string $firstName = null;

    #[ORM\Column(length: 180)]
    private ?string $email = null;

    #[ORM\Column]
    #[Assert\NotBlank]
    private ?int $employeeNumber = null;

    #[ORM\OneToMany(targetEntity: HourlyRate::class, mappedBy: 'employee')]
    private Collection $hourlyRate;

    #[ORM\Column(nullable: true)]
    private ?float $targetSalary = null;

    #[ORM\Column(nullable: true)]
    private ?float $fixPortion = null;

    #[ORM\OneToMany(targetEntity: Hours::class, mappedBy: 'employee')]
    private Collection $hours;

    /**
     * @var Collection<int, VariablePortion>
     */
    #[ORM\OneToMany(targetEntity: VariablePortion::class, mappedBy: 'employee')]
    private Collection $variablePortion;

    /**
     * @var Collection<int, IncentiveRate>
     */
    #[ORM\OneToMany(targetEntity: IncentiveRate::class, mappedBy: 'employee')]
    private Collection $incentiveRate;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $entry = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $resignation = null;

    #[ORM\Column(nullable: true)]
    private ?float $targetVariablePortion = null;

    #[ORM\Column(nullable: true)]
    private ?float $annualWorkingTime = null;

    #[ORM\Column(nullable: true)]
    private ?float $fixVariablePortion = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $fixVariableEntry = null;

    /**
     * @var Collection<int, Goodies>
     */
    #[ORM\OneToMany(targetEntity: Goodies::class, mappedBy: 'employee')]
    private Collection $goodies;

    /**
     * @var Collection<int, Riffle>
     */
    #[ORM\OneToMany(targetEntity: Riffle::class, mappedBy: 'employee')]
    private Collection $riffles;

    /**
     * @var Collection<int, Bonus>
     */
    #[ORM\OneToMany(targetEntity: Bonus::class, mappedBy: 'employee')]
    private Collection $bonuses;

    /**
     * @var Collection<int, Benefits>
     */
    #[ORM\OneToMany(targetEntity: Benefits::class, mappedBy: 'employee')]
    private Collection $benefits;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $info = null;

    public function __construct()
    {
        $this->hours = new ArrayCollection();
        $this->hourlyRate = new ArrayCollection();
        $this->variablePortion = new ArrayCollection();
        $this->incentiveRate = new ArrayCollection();
        $this->goodies = new ArrayCollection();
        $this->bonuses = new ArrayCollection();
        $this->riffles = new ArrayCollection();
        $this->benefits = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getEmployeeNumber(): ?int
    {
        return $this->employeeNumber;
    }

    public function setEmployeeNumber(int $employeeNumber): static
    {
        $this->employeeNumber = $employeeNumber;

        return $this;
    }

    public function getTargetSalary(): ?float
    {
        return $this->targetSalary;
    }

    public function setTargetSalary(?float $targetSalary): static
    {
        $this->targetSalary = $targetSalary;

        return $this;
    }

    public function getFixPortion(): ?float
    {
        return $this->fixPortion;
    }

    public function setFixPortion(?float $fixPortion): static
    {
        $this->fixPortion = $fixPortion;

        return $this;
    }

    /**
     * @return Collection<float, HourlyRate>
     */
    public function getHourlyRate(): Collection
    {
        return $this->hourlyRate;
    }

    public function addHourlyRate(HourlyRate $hourlyRate): static
    {
        if (!$this->hourlyRate->contains($hourlyRate)) {
            $this->hourlyRate->add($hourlyRate);
            $hourlyRate->setEmployee($this);
        }

        return $this;
    }

    public function removeHourlyRate(HourlyRate $hourlyRate): static
    {
        if ($this->hours->removeElement($hourlyRate)) {
            // set the owning side to null (unless already changed)
            if ($hourlyRate->getEmployee() === $this) {
                $hourlyRate->setEmployee(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<float, Hours>
     */
    public function getHours(): Collection
    {
        return $this->hours;
    }

    public function addHour(Hours $hours): static
    {
        if (!$this->hours->contains($hours)) {
            $this->hours->add($hours);
            $hours->setEmployee($this);
        }

        return $this;
    }

    public function removeHour(Hours $hours): static
    {
        if ($this->hours->removeElement($hours)) {
            // set the owning side to null (unless already changed)
            if ($hours->getEmployee() === $this) {
                $hours->setEmployee(null);
            }
        }

        return $this;
    }

    public function getVariablePortion(): Collection
    {
        return $this->variablePortion;
    }

    public function setVariablePortion(Collection $variablePortion): void
    {
        $this->variablePortion = $variablePortion;
    }

    public function addVariablePortion(VariablePortion $variablePortion): static
    {
        if (!$this->variablePortion->contains($variablePortion)) {
            $this->variablePortion->add($variablePortion);
            $variablePortion->setEmployee($this);
        }

        return $this;
    }

    public function removeVariablePortion(VariablePortion $variablePortion): static
    {
        if ($this->variablePortion->removeElement($variablePortion)) {
            // set the owning side to null (unless already changed)
            if ($variablePortion->getEmployee() === $this) {
                $variablePortion->setEmployee(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, IncentiveRate>
     */
    public function getIncentiveRate(): Collection
    {
        return $this->incentiveRate;
    }

    public function addIncentiveRate(IncentiveRate $incentiveRate): static
    {
        if (!$this->incentiveRate->contains($incentiveRate)) {
            $this->incentiveRate->add($incentiveRate);
            $incentiveRate->setEmployee($this);
        }

        return $this;
    }

    public function removeIncentiveRate(IncentiveRate $incentiveRate): static
    {
        if ($this->incentiveRate->removeElement($incentiveRate)) {
            // set the owning side to null (unless already changed)
            if ($incentiveRate->getEmployee() === $this) {
                $incentiveRate->setEmployee(null);
            }
        }

        return $this;
    }

    public function getEntry(): ?\DateTimeInterface
    {
        return $this->entry;
    }

    public function setEntry(?\DateTimeInterface $entry): static
    {
        $this->entry = $entry;

        return $this;
    }

    public function getResignation(): ?\DateTimeInterface
    {
        return $this->resignation;
    }

    public function setResignation(?\DateTimeInterface $resignation): static
    {
        $this->resignation = $resignation;

        return $this;
    }

    public function getFixVariableEntry(): ?\DateTimeInterface
    {
        return $this->fixVariableEntry;
    }

    public function setFixVariableEntry(?\DateTimeInterface $fixVariableEntry): static
    {
        $this->fixVariableEntry = $fixVariableEntry;

        return $this;
    }

    public function getTargetVariablePortion(): ?float
    {
        return $this->targetVariablePortion;
    }

    public function setTargetVariablePortion(?float $targetVariablePortion): static
    {
        $this->targetVariablePortion = $targetVariablePortion;

        return $this;
    }

    public function getAnnualWorkingTime(): ?float
    {
        return $this->annualWorkingTime;
    }

    public function setAnnualWorkingTime(?float $annualWorkingTime): static
    {
        $this->annualWorkingTime = $annualWorkingTime;

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

    /**
     * @return Collection<int, Goodies>
     */
    public function getGoodies(): Collection
    {
        return $this->goodies;
    }

    public function addGoody(Goodies $goody): static
    {
        if (!$this->goodies->contains($goody)) {
            $this->goodies->add($goody);
            $goody->setEmployee($this);
        }

        return $this;
    }

    public function removeGoody(Goodies $goody): static
    {
        if ($this->goodies->removeElement($goody)) {
            // set the owning side to null (unless already changed)
            if ($goody->getEmployee() === $this) {
                $goody->setEmployee(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Riffle>
     */
    public function getRiffles(): Collection
    {
        return $this->riffles;
    }

    public function addRiffle(Riffle $riffle): static
    {
        if (!$this->riffles->contains($riffle)) {
            $this->riffles->add($riffle);
            $riffle->setEmployee($this);
        }

        return $this;
    }

    public function removeRiffle(Riffle $riffle): static
    {
        if ($this->riffles->removeElement($riffle)) {
            // set the owning side to null (unless already changed)
            if ($riffle->getEmployee() === $this) {
                $riffle->setEmployee(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Bonus>
     */
    public function getBonuses(): Collection
    {
        return $this->bonuses;
    }

    public function addBonus(Bonus $bonus): static
    {
        if (!$this->bonuses->contains($bonus)) {
            $this->bonuses->add($bonus);
            $bonus->setEmployee($this);
        }

        return $this;
    }

    public function removeBonus(Bonus $bonus): static
    {
        if ($this->bonuses->removeElement($bonus)) {
            // set the owning side to null (unless already changed)
            if ($bonus->getEmployee() === $this) {
                $bonus->setEmployee(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Benefits>
     */
    public function getBenefits(): Collection
    {
        return $this->benefits;
    }

    public function addBenefit(Benefits $benefit): static
    {
        if (!$this->benefits->contains($benefit)) {
            $this->benefits->add($benefit);
            $benefit->setEmployee($this);
        }

        return $this;
    }

    public function removeBenefit(Benefits $benefit): static
    {
        if ($this->benefits->removeElement($benefit)) {
            // set the owning side to null (unless already changed)
            if ($benefit->getEmployee() === $this) {
                $benefit->setEmployee(null);
            }
        }

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
