<?php

namespace App\Repository;

use App\Entity\Employee;
use App\Entity\Riffle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Riffle>
 *
 * @method Riffle|null find($id, $lockMode = null, $lockVersion = null)
 * @method Riffle|null findOneBy(array $criteria, array $orderBy = null)
 * @method Riffle[]    findAll()
 * @method Riffle[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RiffleRepository extends ServiceEntityRepository
{
    public function __construct(private readonly ManagerRegistry $registry)
    {
        parent::__construct($this->registry, Riffle::class);
    }

    public function add(Riffle $hours): void
    {
        $manager = $this->getEntityManager();
        $manager->persist($hours);
        $manager->flush();
    }

    public function flush(): void
    {
        $this->getEntityManager()->flush();
    }

    public function remove(Riffle $riffle): void
    {
        $this->getEntityManager()->remove($riffle);
        $this->getEntityManager()->flush();
    }

    public function setValues(Riffle $riffle, Employee $employee): void
    {
        $acquiredHoursCollection[0]['month'] = $riffle->getMonth()*1;
        $acquiredHoursCollection[0]['year'] = $riffle->getYear();
        $acquiredHoursCollection[0]['isAcquired'] = $riffle->isAcquiredHours();
        $riffle->setAcquiredHoursCollection($acquiredHoursCollection);

        $employee->addRiffle($riffle);
        $this->getEntityManager()->persist($riffle);
        $this->getEntityManager()->flush();
    }
}
