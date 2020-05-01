<?php

namespace App\Repository;

use App\Entity\CsvKeyValues;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CsvKeyValues|null find($id, $lockMode = null, $lockVersion = null)
 * @method CsvKeyValues|null findOneBy(array $criteria, array $orderBy = null)
 * @method CsvKeyValues[]    findAll()
 * @method CsvKeyValues[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CsvKeyValuesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CsvKeyValues::class);
    }


    /**
     * @return CsvKeyValues[] Returns an array of CsvKeyValues objects
     */
    public function findAllByName()
    {
        return $this->createQueryBuilder('c')
            ->select('c.name')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }

    /**
     * @return CsvKeyValues[] Returns an array of CsvKeyValues objects
     */
    public function findDistinctValue()
    {
        return $this->createQueryBuilder('c')
            ->select('c.asValue')
            ->distinct()
            ->getQuery()
            ->getResult();
    }

    /**
     * @return CsvKeyValues[] Returns an array of CsvKeyValues objects
     */
    public function findAllByType()
    {
        return $this->createQueryBuilder('c')
            ->select('c.type')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }

    // /**
    //  * @return CsvKeyValues[] Returns an array of CsvKeyValues objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CsvKeyValues
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
