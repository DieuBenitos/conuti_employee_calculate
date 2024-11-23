<?php

namespace App\Repository;

use App\Entity\BenefitsType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<BenefitsType>
 *
 * @method BenefitsType|null find($id, $lockMode = null, $lockVersion = null)
 * @method BenefitsType|null findOneBy(array $criteria, array $orderBy = null)
 * @method BenefitsType[]    findAll()
 * @method BenefitsType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BenefitsTypeRepository extends ServiceEntityRepository
{
    public function __construct(private readonly ManagerRegistry $registry)
    {
        parent::__construct($this->registry, BenefitsType::class);
    }

    public function add(BenefitsType $benefitsType): void
    {
        $manager = $this->getEntityManager();
        $manager->persist($benefitsType);
        $manager->flush();
    }

    public function flush(): void
    {
        $this->getEntityManager()->flush();
    }

    public function remove(BenefitsType $benefitsType): void
    {
        $this->getEntityManager()->remove($benefitsType);
    }
}
