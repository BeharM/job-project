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

    /**
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function belongsJobToUser($jobId, $userId){
        return $this->createQueryBuilder('user_job')
            ->where('user_job.job = :jobId')
            ->andWhere('user_job.user = :userId')
            ->setParameters(['jobId' => $jobId, 'userId' => $userId])
            ->getQuery()
            ->getOneOrNullResult();
    }
}
