<?php

namespace App\Repository;

use App\Entity\Job;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @extends ServiceEntityRepository<Job>
 *
 * @method Job|null find($id, $lockMode = null, $lockVersion = null)
 * @method Job|null findOneBy(array $criteria, array $orderBy = null)
 * @method Job[]    findAll()
 * @method Job[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class JobRepository extends ServiceEntityRepository
{
    private  $entityManager;
    private  $validator;
    public  $errorResponse = false;
    public function __construct(ValidatorInterface $validator, ManagerRegistry $registry, EntityManagerInterface $entityManager)
    {
        parent::__construct($registry, Job::class);
        $this->entityManager = $entityManager;
        $this->validator = $validator;
    }

    /**
     * Store new job
     */
    public function store($request){
        $job = new Job();
        $job->setTitle($request->get('title'));
        $job->setDescription($request->get('description'));
        $job->setCreatedAt(new \DateTimeImmutable('now'));
        try {
            $errors =  $this->validator->validate($job);
            if (count($errors) > 0) {
                $errorsString = (string) $errors;
                $this->errorResponse = $errorsString;
                return false;
            }
            $this->entityManager->persist($job);
            $this->entityManager->flush();
            return $job;
        }catch (\Exception $e){
            return false;
        }
    }

    /**
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function isJobAssigned($jobId){
        return $this->createQueryBuilder('j')
            ->innerJoin('App\Entity\UserJob', 'uJ', 'WITH', 'uJ.job = j.id')
            ->andWhere('j.id = :jobId')
            ->setParameter('jobId', $jobId)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
