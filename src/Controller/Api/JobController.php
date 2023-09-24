<?php

namespace App\Controller\Api;

use App\Entity\Job;
use App\Entity\UserJob;
use App\Resource\JobCollectionResource;
use App\Resource\JobResource;
use Doctrine\Persistence\ManagerRegistry;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/api/job', name: 'api_job_')]
class JobController extends AbstractController
{

    /**
     *
     * @OA\Response(
     *     response=200,
     *     description="List of jobs",
     *     @OA\JsonContent(
     *         type="array",
     *         @OA\Items(ref=@Model(type=Job::class))
     *     )
     * )
     * @return JsonResponse
     */
    #[Route('/lists', name: 'lists', methods: ['GET'])]
    public function index(ManagerRegistry $doctrine): JsonResponse
    {
        $em = $doctrine->getManager();
        $jobs = $em->getRepository(Job::class)->findAll();
        $jobsCollectionResource = new JobCollectionResource($jobs);

        return $this->json([
            'code'=>200,
            'message' => 'Success!',
            'data' => $jobsCollectionResource
        ]);
    }

    /**
     * Show job details.
     *
     * @OA\Get(
     *     summary="Show job details",
     *     tags={"Job Management"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Job ID",
     *         required=true,
     *         @OA\Schema(
     *            type="integer",
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Show Job details",
     *         @Model(type=Job::class)
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Job not found",
     *         @OA\Schema(
     *             type="object",
     *             @OA\Property(property="message", type="string", description="Error message")
     *         )
     *     )
     * )
     *
     * @param int $id
     * @return JsonResponse
     */
    #[Route('/show/{id}', name: 'show', methods: ['GET'])]
    public function show(ManagerRegistry $doctrine, $id): JsonResponse
    {
        $em = $doctrine->getManager();
        $jobRepository = $em->getRepository(Job::class);
        if ($job = $jobRepository->find($id)) {
            $jobsResource = new JobResource($job);
            return $this->json([
                'code'=>200,
                'message' => 'Success!',
                'data' => $jobsResource
            ]);
        }else{
            return $this->json([
                'code' => 400,
                'data' => false,
                'message' => 'Job Not Found!'
            ]);
        }
    }

    /**
     * Create a new job.
     *
     * @OA\Post(
     *     summary="Register a new job",
     *     tags={"Job Management"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(property="title", type="string", example="Symfony Tasks", description="Job Titile"),
     *                 @OA\Property(property="description", type="string", example="Api Job CRUD", description="Job Description", nullable=true),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Job stored successfully",
     *         @Model(type=Job::class)
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad request, validation failed",
     *         @OA\Schema(
     *             type="object",
     *             @OA\Property(property="message", type="string", description="Error message"),
     *             @OA\Property(property="errors", type="object", description="Validation errors")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error",
     *         @OA\Schema(
     *             type="object",
     *             @OA\Property(property="message", type="string", description="Error message")
     *         )
     *     )
     * )
     *
     * @return JsonResponse
     */
    #[Route('/store', name: 'store', methods: ['POST'])]
    public function store(ManagerRegistry $doctrine, Request $request): JsonResponse
    {
        try {
            $em = $doctrine->getManager();
            $jobRepository = $em->getRepository(Job::class);
            if ($request->get('title')) {
                if ($job = $jobRepository->store($request)) {
                    return $this->json([
                        'code' => 200,
                        'data' => new JobResource($job),
                        'message' => 'Created Successfully'
                    ]);
                }else{
                    $errorResponse = $jobRepository->errorResponse;
                    return $this->json([
                        'code'=>400,
                        'data'=>false,
                        'message' => $errorResponse
                    ]);
                }
            }else{
                return $this->json([
                    'code'=>400,
                    'data'=>false,
                    'message' => 'Title is required'
                ]);
            }
        }catch (\Exception $e){
            return $this->json([
                'code'=>$e->getCode(),
                'data'=>false,
                'message' => $e->getMessage()
            ]);
        }

    }

    /**
     * Assign user to a job.
     *
     * @OA\Post(
     *     summary="Assign job to a user",
     *     tags={"Schedule Job"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Job ID",
     *         required=true,
     *         @OA\Schema(
     *            type="integer",
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(property="scheduled_at", type="string", example="2023/09/25 15:30:00", description="Assignment timestamp", nullable=false),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Job assigned successfully",
     *         @OA\Schema(
     *             type="object",
     *             @OA\Property(property="message", type="string", description="Success message")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad request, validation failed",
     *         @OA\Schema(
     *             type="object",
     *             @OA\Property(property="message", type="string", description="Error message"),
     *             @OA\Property(property="errors", type="object", description="Validation errors")
     *         )
     *     )
     * )
     *
     * @param int $id
     * @param Request $request
     * @return JsonResponse
     */
    #[IsGranted('ROLE_AUDITOR')]
    #[Route('/assign/{id}',  name: 'assign', methods: ['POST'])]
    public function assign(ManagerRegistry $doctrine, Request $request, $id): JsonResponse
    {
        $em = $doctrine->getManager();
        $jobRepository = $em->getRepository(Job::class);
        $currentUser = $this->getUser();
        $today = new \DateTimeImmutable('now');
        if ($currentUser and in_array("ROLE_AUDITOR", $currentUser->getRoles())) {
            $em->getConnection()->beginTransaction();
            $em->getConnection()->setAutoCommit(false);
            try {
                $scheduleAt = $request->get('scheduled_at');

                if ($job = $jobRepository->find($id)) {
                    if ($jobRepository->isJobAssigned($job->getId())){
                        $error ='Error! Job belongs to an auditor!Is assigned';
                    }else {
                        $scheduleFormat = new \DateTimeImmutable($scheduleAt);
                        if ($scheduleAt and $scheduleFormat >= $today) {
                            $userJob = new UserJob();
                            $userJob->setUser($currentUser);
                            $userJob->setJob($job);
                            $userJob->setCreatedAt(new \DateTimeImmutable('now'));
                            $userJob->setScheduledAt($scheduleFormat);

                            $em->persist($userJob);
                            $em->flush();
                        }else{
                            $error = 'scheduled_at is required and should be grater than current datetime';
                        }
                    }
                }else{
                    $error ='An error has occurred! Job not found';
                }

                if (isset($error)) {
                    $em->getConnection()->rollback();
                    return $this->json([
                        'code' => 400,
                        'data' => false,
                        'message' => $error
                    ]);
                }else{
                    $em->getConnection()->commit();
                    return $this->json([
                        'code' => 200,
                        'data' => new JobResource($job),
                        'message' => 'Job assigned successfully'
                    ]);
                }
            } catch (\Exception $exception) {
                $em->getConnection()->rollback();
                return $this->json([
                    'code' => $exception->getCode(),
                    'data' => false,
                    'message' => $exception->getMessage()
                ]);
            }
        }
        return $this->json([
            'code' => 401,
            'data' => false,
            'message' => 'You Are Not Authorized! Please Log In!!'
        ]);
    }

    /**
     * Mark a job as completed.
     *
     * @OA\Put(
     *     summary="Mark a job as completed",
     *     tags={"Schedule Job"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Job ID",
     *         required=true,
     *         @OA\Schema(
     *            type="integer",
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(property="assessment", type="text", example="Task Code or Description", description="Job Assessment", nullable=false),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Job marked as completed successfully",
     *         @Model(type=Job::class)
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Job not found",
     *         @OA\Schema(
     *             type="object",
     *             @OA\Property(property="message", type="string", description="Error message")
     *         )
     *     )
     * )
     *
     * @param int $id
     * @param Request $request
     * @return JsonResponse
     */
    #[IsGranted('ROLE_AUDITOR')]
    #[Route('/complete/{id}',  name: 'complete', methods: ['PUT'])]
    public function complete(ManagerRegistry $doctrine, Request $request, $id): JsonResponse
    {
        $em = $doctrine->getManager();
        $jobRepository = $em->getRepository(Job::class);
        $userJobRepository = $em->getRepository(UserJob::class);
        $currentUser = $this->getUser();
        if ($currentUser) {
            $em->getConnection()->beginTransaction();
            $em->getConnection()->setAutoCommit(false);
            try {
                if ($job = $jobRepository->find($id)) {
                    if (!$userJob = $userJobRepository->belongsJobToUser($id, $currentUser->getId())){
                        $error ='Error! You can not update this job, does not belong to you!';
                    }else {
                        $userJob->setAssessment($request->get('assessment'));
                        $userJob->setStatus(1);
                        $userJob->setUpdatedAt(new \DateTimeImmutable('now'));

                        $em->persist($userJob);
                        $em->flush();
                    }
                }else{
                    $error ='An error has occurred! Job not found';
                }

                if (isset($error)) {
                    $em->getConnection()->rollback();
                    return $this->json([
                        'code' => 400,
                        'data' => false,
                        'message' => $error
                    ]);
                }else{
                    $em->getConnection()->commit();
                    return $this->json([
                        'code' => 200,
                        'data' => new JobResource($job),
                        'message' => 'Job Updated Successfully'
                    ]);
                }
            } catch (\Exception $exception) {
                $em->getConnection()->rollback();
                return $this->json([
                    'code' => $exception->getCode(),
                    'data' => false,
                    'message' => $exception->getMessage()
                ]);
            }
        }
        return $this->json([
            'code' => 401,
            'data' => false,
            'message' => 'You Are Not Authorized! Please Log In!!'
        ]);
    }
}