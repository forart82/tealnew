<?php

namespace App\Repository;

use App\Entity\Navigations;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Navigations|null find($id, $lockMode = null, $lockVersion = null)
 * @method Navigations|null findOneBy(array $criteria, array $orderBy = null)
 * @method Navigations[]    findAll()
 * @method Navigations[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NavigationsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Navigations::class);
    }

    // /**
    //  * @return Navigations[] Returns an array of Navigations objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('n.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Navigations
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
