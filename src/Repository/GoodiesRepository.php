<?php

namespace App\Repository;

use App\Entity\Employee;
use App\Entity\Goodies;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Goodies>
 *
 * @method Goodies|null find($id, $lockMode = null, $lockVersion = null)
 * @method Goodies|null findOneBy(array $criteria, array $orderBy = null)
 * @method Goodies[]    findAll()
 * @method Goodies[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GoodiesRepository extends ServiceEntityRepository
{
    public function __construct(private readonly ManagerRegistry $registry)
    {
        parent::__construct($this->registry, Goodies::class);
    }

    public function add(Goodies $goodies): void
    {
        $manager = $this->getEntityManager();
        $goodies->setModificationDate(new \DateTime());
        $manager->persist($goodies);
        $manager->flush();
    }

    public function flush(): void
    {
        $this->getEntityManager()->flush();
    }

    public function remove(Goodies $goodies): void
    {
        $this->getEntityManager()->remove($goodies);
        $this->getEntityManager()->flush();
    }

    public function setValues(Goodies $goodies, Employee $employee): void
    {
        $divider = $goodies->getDivider();

        $date = new DateTime();
        $date->setDate($goodies->getYear(),$goodies->getMonth(),1);

        if (empty($goodies->getAmortization()) || $goodies->isChanged()) {
            $amortizationArray = [];
            for ($i = 1; $i <= $divider; $i++) {
                $amortizationArray[$i]["month"] = $date->add(new \DateInterval('P1M'))->format("m");
                $amortizationArray[$i]["year"] = $date->format("Y");
                $amortizationArray[$i]["amount"] = $goodies->getPartialAmounts();
                $amortizationArray[$i]["charged"] = "";
            }
            $goodies->setAmortization($amortizationArray);
            $goodies->setChanged(false);
        }

        $employee->addGoody($goodies);
        $this->getEntityManager()->persist($goodies);
        $this->getEntityManager()->flush();
    }
}
