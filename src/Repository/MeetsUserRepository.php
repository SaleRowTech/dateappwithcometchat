<?php

namespace App\Repository;

use App\Entity\MeetsUser;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MeetsUser|null find($id, $lockMode = null, $lockVersion = null)
 * @method MeetsUser|null findOneBy(array $criteria, array $orderBy = null)
 * @method MeetsUser[]    findAll()
 * @method MeetsUser[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MeetsUserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MeetsUser::class);
    }

    // /**
    //  * @return MeetsUser[] Returns an array of MeetsUser objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?MeetsUser
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
