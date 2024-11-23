<?php

namespace App\Repository;

use App\Entity\Employee;
use App\Entity\IncentiveRate;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<IncentiveRate>
 *
 * @method IncentiveRate|null find($id, $lockMode = null, $lockVersion = null)
 * @method IncentiveRate|null findOneBy(array $criteria, array $orderBy = null)
 * @method IncentiveRate[]    findAll()
 * @method IncentiveRate[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IncentiveRateRepository extends ServiceEntityRepository
{
    public function __construct(private readonly ManagerRegistry $registry)
    {
        parent::__construct($this->registry, IncentiveRate::class);
    }

    public function add(IncentiveRate $incentiveRate): void
    {
        $manager = $this->getEntityManager();
        $manager->persist($incentiveRate);
        $manager->flush();
    }

    public function flush(): void
    {
        $this->getEntityManager()->flush();
    }

    public function remove(IncentiveRate $incentiveRate): void
    {
        $this->getEntityManager()->remove($incentiveRate);
    }

    public function setValues(IncentiveRate $incentiveRate, float $data, Employee $employee): void
    {
        $incentiveRate->setIncentiveRate($data);
        $incentiveRate->setModificationDate(new \DateTime());
        $employee->addIncentiveRate($incentiveRate);
        $this->getEntityManager()->persist($incentiveRate);
        $this->getEntityManager()->flush();
    }
}
