<?php

namespace App\Repository;

use App\Entity\Svg;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Svg|null find($id, $lockMode = null, $lockVersion = null)
 * @method Svg|null findOneBy(array $criteria, array $orderBy = null)
 * @method Svg[]    findAll()
 * @method Svg[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SvgRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Svg::class);
    }

    public function findLikeAnswer($answer)
    {
        return $this->createQueryBuilder('i')
        ->andWhere('i.name LIKE :answer')
        ->setParameter('answer', '%'.$answer.'%')
        ->orderBy('i.id', 'DESC')
        ->getQuery()
        ->getResult()
        ;
    }

    public function findOneByNameAndDirectory($name, $dir): ?Svg
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.name = :name')
            ->andWhere('i.category = :dir')
            ->setParameter('name', $name)
            ->setParameter('dir', $dir)
            ->getQuery()
            ->getOneOrNullResult();
    }

    // /**
    //  * @return Svg[] Returns an array of Svg objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Svg
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
