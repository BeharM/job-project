<?php

namespace App\Controller\Api;

use App\Service\Helper;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;

#[Route('/api', name: 'api_')]
class DashboardController extends AbstractController
{
    private  $helper;
    public function __construct(Helper $helper)
    {
        $this->helper = $helper;
    }

    /**
     * @OA\Response(
     *     response=200,
     *     description="List of Available Zones",
     * )
     */
    #[Route('/zones', name: 'zones', methods: ['GET'])]
    public function zones(): JsonResponse
    {
        $zones = $this->helper->getZones();

        return $this->json([
            'code'=>200,
            'message' => 'Success!',
            'data' => $zones
        ]);
    }

    /**
     * @OA\Response(
     *     response=200,
     *     description="List of Available Roles",
     * )
     */
    #[Route('/roles', name: 'roles', methods: ['GET'])]
    public function roles(): JsonResponse
    {
        $zones = $this->helper->getRoles();

        return $this->json([
            'code'=>200,
            'message' => 'Success!',
            'data' => $zones
        ]);
    }
}