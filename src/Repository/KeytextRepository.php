<?php

namespace App\Repository;

use App\Entity\Keytext;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Keytext|null find($id, $lockMode = null, $lockVersion = null)
 * @method Keytext|null findOneBy(array $criteria, array $orderBy = null)
 * @method Keytext[]    findAll()
 * @method Keytext[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class KeytextRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Keytext::class);
    }

    // /**
    //  * @return Keytext[] Returns an array of Keytext objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('k')
            ->andWhere('k.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('k.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Keytext
    {
        return $this->createQueryBuilder('k')
            ->andWhere('k.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
