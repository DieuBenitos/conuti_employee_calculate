<?php

namespace App\Repository;

use App\Entity\Employee;
use App\Entity\VariablePortion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<VariablePortion>
 *
 * @method VariablePortion|null find($id, $lockMode = null, $lockVersion = null)
 * @method VariablePortion|null findOneBy(array $criteria, array $orderBy = null)
 * @method VariablePortion[]    findAll()
 * @method VariablePortion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VariablePortionRepository extends ServiceEntityRepository
{
    public function __construct(private readonly ManagerRegistry $registry)
    {
        parent::__construct($this->registry, VariablePortion::class);
    }

    public function add(VariablePortion $variablePortion): void
    {
        $manager = $this->getEntityManager();
        $manager->persist($variablePortion);
        $manager->flush();
    }

    public function flush(): void
    {
        $this->getEntityManager()->flush();
    }

    public function remove(VariablePortion $variablePortion): void
    {
        $this->getEntityManager()->remove($variablePortion);
    }

    public function findPortionsByEmployeeAndYear(Employee $employee, string $year, string $order): ?array
    {
        return $this->createQueryBuilder('v')
            ->select('v.portions')
            ->andWhere('v.employee = :employee')
            ->setParameter('employee', $employee)
            ->andWhere('v.payoutYear = :year')
            ->setParameter('year', $year)
            ->orderBy('v.payoutMonth', $order)
            ->getQuery()
            ->getArrayResult()
            ;
    }

    public function findByEmployeeIdsMonthsAndYears(int $employeeId, int $month, int $year): ?VariablePortion
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.employee = (:ids)')
            ->setParameter('ids', $employeeId)
            ->andWhere('v.payoutMonth = :month')
            ->setParameter('month', $month)
            ->andWhere('v.payoutYear = :year')
            ->setParameter('year', $year)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function getYears(): ?array
    {
        return $this->createQueryBuilder('v')
            ->select('v.payoutYear')
            ->groupBy('v.payoutYear')
            ->getQuery()
            ->getArrayResult();
    }
    public function setValues(VariablePortion $variablePortion, float $data, Employee $employee): void
    {
        $variablePortion->setPortions($data);
        $variablePortion->setPayoutMonth(new \DateTime());
        $employee->addVariablePortion($variablePortion);
        $this->getEntityManager()->persist($variablePortion);
        $this->getEntityManager()->flush();
    }
}
