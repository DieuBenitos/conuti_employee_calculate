<?php

namespace App\Repository;

use App\Entity\Employee;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Employee>
 *
 * @method Employee|null find($id, $lockMode = null, $lockVersion = null)
 * @method Employee|null findOneBy(array $criteria, array $orderBy = null)
 * @method Employee[]    findAll()
 * @method Employee[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EmployeeRepository extends ServiceEntityRepository
{
    public function __construct(private readonly ManagerRegistry $registry)
    {
        parent::__construct($this->registry, Employee::class);
    }

    public function add(Employee $employee): void
    {
        $manager = $this->getEntityManager();
        $manager->persist($employee);
        $manager->flush();
    }

    public function flush(): void
    {
        $this->getEntityManager()->flush();
    }

    public function remove(Employee $employee): void
    {
        $this->getEntityManager()->remove($employee);
    }

    public function findOneById($value): ?Employee
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.id = :id')
            ->setParameter('id', $value)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function getAllIds(): array
    {
        return $this->createQueryBuilder('e')
            ->select('e.id')
            ->getQuery()
            ->getSingleColumnResult();
    }

    public function getNameById(int $id): array
    {
        return $this->createQueryBuilder('e')
            ->select('e.name', 'e.firstName')
            ->where('e.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getResult();
    }

    public function setValues(Employee $employee, array $data): void
    {
        $employee->setName($data["name"]);
        $employee->setFirstName($data["firstname"]);
        $employee->setEmployeeNumber($data["employeeNumber"]);
        $employee->setEntry($data["entry"]);
        $employee->setResignation($data["resignation"]);
        $employee->setAnnualWorkingTime($data["annualWorkingTime"]);
        $employee->setTargetSalary($data["targetSalary"]);
        $employee->setTargetVariablePortion($data["targetVariablePortion"]);
        $employee->setFixPortion($data["fixPortion"]);
        $employee->setInfo($data["info"]);
        $employee->setFixVariablePortion($data["fixVariablePortion"]);
        $employee->setFixVariableEntry($data["fixVariableEntry"]);
        $employee->setEmail($data["email"]);
        $this->add($employee);
    }
}
