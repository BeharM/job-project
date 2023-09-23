<?php

namespace App\Controller\Api;

use App\Entity\Job;
use App\Resource\JobCollectionResource;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/api', name: 'api_')]
#[IsGranted('ROLE_AUDITOR')]
class JobController extends AbstractController
{
    #[Route('/jobs', name: 'jobs')]
    public function index(ManagerRegistry $doctrine, Request $request): JsonResponse
    {
        $em = $doctrine->getManager();
        $jobs = $em->getRepository(Job::class)->findAll();
        $jobsCollectionResource = new JobCollectionResource($jobs);

//        dd($jobsCollectionResource);
        return $this->json([
            'code'=>200,
            'message' => 'Success!',
            'data' => $jobsCollectionResource
        ]);
    }
}