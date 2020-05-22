<?php

namespace App\Repository;

use App\Entity\Result;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Result|null find($id, $lockMode = null, $lockVersion = null)
 * @method Result|null findOneBy(array $criteria, array $orderBy = null)
 * @method Result[]    findAll()
 * @method Result[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ResultRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Result::class);
    }

    public function allResultsCompany(int $id)
    {
        return $this->createQueryBuilder('r')
            ->join('r.user', 'u')
            ->join('u.company', 'c')
            ->where('c.id=:id')
            ->setParameter('id', $id)
            ->andWhere('r.choice > :val')
            ->setParameter('val', 0)
            ->getQuery()
            ->getResult();
    }

    public function allResultNotZeroUser($user)
    {
        return $this->createQueryBuilder('r')
            ->where('r.choice > :val')
            ->setParameter('val', 0)
            ->andWhere('r.user=:user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();
    }

    public function allResultNotZeroCompany($companyId)
    {
        return $this->createQueryBuilder('r')
            ->join('r.user', 'u')
            ->join('u.company', 'c')
            ->where('c.id=:companyId')
            ->setParameter('companyId', $companyId)
            ->andWhere('r.choice > :val')
            ->setParameter('val', 0)
            ->orderBy('r.subject', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function allNotationsCompany(int $id)
    {
        return $this->createQueryBuilder('r')
            ->join('r.user', 'u')
            ->join('u.company', 'c')
            ->where('c.id=:id')
            ->setParameter('id', $id)
            ->andWhere('r.notation > :val')
            ->setParameter('val', 0)
            ->getQuery()
            ->getResult();
    }

    public function allNotationNotZeroUser($user)
    {
        return $this->createQueryBuilder('r')
            ->where('r.notation > :val')
            ->setParameter('val', 0)
            ->andWhere('r.user=:user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();
    }



    // /**
    //  * @return Result[] Returns an array of Result objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Result
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
