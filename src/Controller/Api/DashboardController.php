<?php

namespace App\Controller\Api;

use App\Service\Helper;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
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
     * @Route("/zones", methods={"GET"})
     *
     * @OA\Response(
     *     response=200,
     *     description="List of Avaliable Zones, used to register new user",
     * )
     */
    #[Route('/zones', name: 'zones')]
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
     * @Route("/roles", methods={"GET"})
     *
     * @OA\Response(
     *     response=200,
     *     description="List of Avaliables Roles, assigne role to user on register",
     * )
     */
    #[Route('/roles', name: 'roles')]
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