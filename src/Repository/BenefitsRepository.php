<?php

namespace App\Repository;

use App\Entity\Benefits;
use App\Entity\BenefitsType;
use App\Entity\Employee;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Benefits>
 *
 * @method Benefits|null find($id, $lockMode = null, $lockVersion = null)
 * @method Benefits|null findOneBy(array $criteria, array $orderBy = null)
 * @method Benefits[]    findAll()
 * @method Benefits[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BenefitsRepository extends ServiceEntityRepository
{
    public function __construct(
        private readonly ManagerRegistry $registry,
        private readonly BenefitsTypeRepository $benefitsTypeRepository,
    )
    {
        parent::__construct($this->registry, Benefits::class);
    }

    public function add(Benefits $benefits): void
    {
        $manager = $this->getEntityManager();
        $manager->persist($benefits);
        $manager->flush();
    }

    public function flush(): void
    {
        $this->getEntityManager()->flush();
    }

    public function remove(Benefits $benefits): void
    {
        $this->getEntityManager()->remove($benefits);
        $this->flush();
    }

    public function setValues(array $benefitTypeIds, Employee $employee, array $data): void
    {
        $manager = $this->getEntityManager();
        foreach ($benefitTypeIds as $benefitTypeId) {
            $benefitsType = $this->benefitsTypeRepository->findOneBy(['id' => $benefitTypeId]);
            $benefits = $this->findOneBy(['employee' => $employee, 'benefitsType' => $benefitsType]);

            if (!$benefits instanceof Benefits) {
                $benefits = new Benefits();
                $benefits->setCreationDate(new \DateTime());
                $benefits->setBenefitsType($benefitsType);
                $benefits->setEmployee($employee);
            }

            $date = new \DateTime();
            $benefits->setModificationDate($date);
            $benefits->setMonth($date->format('m'));
            $benefits->setYear($date->format('Y'));
            if(isset($data["benefitsMonetaryBenefit".$benefitTypeId])){
                $benefits->setMonetaryBenefit($data["benefitsMonetaryBenefit".$benefitTypeId]);
            }
            if(isset($data["benefitsPrivatePortion".$benefitTypeId])){
                $benefits->setPrivatePortion($data["benefitsPrivatePortion".$benefitTypeId]);
            }
            $employee->addBenefit($benefits);
            $manager->persist($benefits);
            $manager->flush();
        }
    }
}
