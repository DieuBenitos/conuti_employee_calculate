<?php

namespace App\Entity;

use App\Repository\BenefitsTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BenefitsTypeRepository::class)]
class BenefitsType
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $name = null;

    /**
     * @var Collection<int, Benefits>
     */
    #[ORM\OneToMany(targetEntity: Benefits::class, mappedBy: 'benefitsType')]
    private Collection $benefits;

    #[ORM\Column]
    private ?bool $isMonetaryBenefit = null;

    public function __construct()
    {
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

    /**
     * @return Collection<int, Benefits>
     */
    public function getBenefits(): Collection
    {
        return $this->benefits;
    }

    public function addBenefits(Benefits $benefits): static
    {
        if (!$this->benefits->contains($benefits)) {
            $this->benefits->add($benefits);
            $benefits->setBenefitsType($this);
        }

        return $this;
    }

    public function removeBenefits(Benefits $benefits): static
    {
        if ($this->benefits->removeElement($benefits)) {
            // set the owning side to null (unless already changed)
            if ($benefits->getBenefitsType() === $this) {
                $benefits->setBenefitsType(null);
            }
        }

        return $this;
    }

    public function isMonetaryBenefit(): ?bool
    {
        return $this->isMonetaryBenefit;
    }

    public function setMonetaryBenefit(bool $isMonetaryBenefit): static
    {
        $this->isMonetaryBenefit = $isMonetaryBenefit;

        return $this;
    }
}
