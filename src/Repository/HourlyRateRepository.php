<?php

namespace App\Repository;

use App\Entity\Employee;
use App\Entity\HourlyRate;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<HourlyRate>
 *
 * @method HourlyRate|null find($id, $lockMode = null, $lockVersion = null)
 * @method HourlyRate|null findOneBy(array $criteria, array $orderBy = null)
 * @method HourlyRate[]    findAll()
 * @method HourlyRate[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HourlyRateRepository extends ServiceEntityRepository
{
    public function __construct(private readonly ManagerRegistry $registry)
    {
        parent::__construct($this->registry, HourlyRate::class);
    }

    public function add(HourlyRate $hourlyRate): void
    {
        $manager = $this->getEntityManager();
        $manager->persist($hourlyRate);
        $manager->flush();
    }

    public function flush(): void
    {
        $this->getEntityManager()->flush();
    }

    public function remove(HourlyRate $hourlyRate): void
    {
        $this->getEntityManager()->remove($hourlyRate);
    }

    public function setValues(HourlyRate $hourlyRate, float $data, Employee $employee): void
    {
        $hourlyRate->setHourlyRate($data);
        $hourlyRate->setModificationDate(new \DateTime());
        $employee->addHourlyRate($hourlyRate);
        $this->getEntityManager()->persist($hourlyRate);
        $this->getEntityManager()->flush();
    }
}
