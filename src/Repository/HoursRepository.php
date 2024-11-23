<?php

namespace App\Repository;

use App\Entity\Employee;
use App\Entity\Hours;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Hours>
 *
 * @method Hours|null find($id, $lockMode = null, $lockVersion = null)
 * @method Hours|null findOneBy(array $criteria, array $orderBy = null)
 * @method Hours[]    findAll()
 * @method Hours]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HoursRepository extends ServiceEntityRepository
{
    public function __construct(private readonly ManagerRegistry $registry)
    {
        parent::__construct($this->registry, Hours::class);
    }

    public function add(Hours $hours): void
    {
        $manager = $this->getEntityManager();
        $manager->persist($hours);
        $manager->flush();
    }

    public function flush(): void
    {
        $this->getEntityManager()->flush();
    }

    public function remove(Hours $hours): void
    {
        $this->getEntityManager()->remove($hours);
        $this->getEntityManager()->flush();
    }

    public function setValues(Hours $hours, Employee $employee): void
    {
        $employee->addHour($hours);
        $this->getEntityManager()->persist($hours);
        $this->getEntityManager()->flush();
    }
}
