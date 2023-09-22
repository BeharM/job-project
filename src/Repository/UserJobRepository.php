<?php

namespace App\Repository;

use App\Entity\UserJob;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UserJob>
 *
 * @method UserJob|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserJob|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserJob[]    findAll()
 * @method UserJob[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserJobRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserJob::class);
    }

//    /**
//     * @return UserJob[] Returns an array of UserJob objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('u.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?UserJob
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
