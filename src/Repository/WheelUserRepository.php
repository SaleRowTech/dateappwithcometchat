<?php

namespace App\Repository;

use App\Entity\WheelUser;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method WheelUser|null find($id, $lockMode = null, $lockVersion = null)
 * @method WheelUser|null findOneBy(array $criteria, array $orderBy = null)
 * @method WheelUser[]    findAll()
 * @method WheelUser[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WheelUserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WheelUser::class);
    }

    // /**
    //  * @return WheelUser[] Returns an array of WheelUser objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('w.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?WheelUser
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
