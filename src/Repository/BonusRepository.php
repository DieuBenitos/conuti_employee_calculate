<?php

namespace App\Repository;

use App\Entity\Bonus;
use App\Entity\Employee;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Bonus>
 *
 * @method Bonus|null find($id, $lockMode = null, $lockVersion = null)
 * @method Bonus|null findOneBy(array $criteria, array $orderBy = null)
 * @method Bonus[]    findAll()
 * @method Bonus[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BonusRepository extends ServiceEntityRepository
{
    public function __construct(private readonly ManagerRegistry $registry)
    {
        parent::__construct($this->registry, Bonus::class);
    }

    public function add(Bonus $bonus): void
    {
        $manager = $this->getEntityManager();
        $manager->persist($bonus);
        $manager->flush();
    }

    public function flush(): void
    {
        $this->getEntityManager()->flush();
    }

    public function remove(Bonus $bonus): void
    {
        $this->getEntityManager()->remove($bonus);
        $this->getEntityManager()->flush();
    }

    public function setValues(Bonus $bonus, Employee $employee): void
    {
        if ($bonus->isAdd() === true) {
            $bonus->setSubtract(false);
        } else {
            $bonus->setAdd(false);
            $bonus->setSubtract(true);
        }
        $employee->addBonus($bonus);
        $this->getEntityManager()->persist($bonus);
        $this->getEntityManager()->flush();
    }
}
