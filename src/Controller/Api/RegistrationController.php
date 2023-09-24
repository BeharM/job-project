<?php

namespace App\Controller\Api;

use App\Entity\User;
use App\Service\Helper;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use OpenApi\Annotations as OA;

class RegistrationController extends AbstractController
{
    private  $helper;
    private  $validator;
    public function __construct(Helper $helper, ValidatorInterface $validator,)
    {
        $this->helper = $helper;
        $this->validator = $validator;

    }

    /**
     * Register a new user.
     *
     * @Route("/register", name="register")
     *
     * @OA\Post(
     *     summary="Register a new user",
     *     tags={"User Registration"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(property="email", type="string", description="User's email address", example="user@example.com"),
     *                 @OA\Property(property="password", type="string", description="User's password", example="password123"),
     *                 @OA\Property(property="username", type="string", description="User's username", example="username123", nullable=true),
     *                 @OA\Property(property="fullName", type="string", description="User's full name", example="John Doe", nullable=true),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="User registered successfully",
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
     * @return Response
     */
    #[Route('/register', methods: ['POST'])]
    public function register(ManagerRegistry $doctrine, Request $request, UserPasswordHasherInterface $passwordHasher): Response
    {
        $em = $doctrine->getManager();
        $email = $request->get('email');
        $password = $request->get('password');
        $username = $request->get('username');
        $zoneName = $request->get('zone');
        $roleName = $request->get('role');
        if ($email and $password) {
            $user = new User();
            $hashedPassword = $passwordHasher->hashPassword(
                $user,
                $password
            );
            $user->setPassword($hashedPassword);
            $user->setEmail($email);
            if ($username) {
                $user->setUsername($username);
            }else{
                $user->setUsername($email);
            }

            if ($zoneName and isset($this->helper->getZones()[$zoneName])){
                $zone = $this->helper->getZones()[$zoneName];
                $user->setZone($zone);
            }else{
                return $this->json(['message' => 'Zone is required']);
            }
            if ($roleName){
                if (isset($this->helper->getRoles()[$roleName])){
                    $role[] = $this->helper->getRoles()[$roleName];
                    $user->setRoles($role);
                }else{
                    return $this->json(['message' => 'Selected Role do not exist']);
                }
            }else{
                $user->setRoles([]);
            }
            $user->setCreatedAt(new \DateTimeImmutable('now'));
            $errors =  $this->validator->validate($user);
            if (count($errors) > 0) {
                return $this->json([
                    'code' => 200,
                    'data' => false,
                    'message' => $errors
                ]);
            }
            $em->persist($user);
            $em->flush();

            return $this->json([
                'code' => 200,
                'data' => $user,
                'message' => 'Registered Successfully'
            ]);
        }

        return $this->json([
            'code' => 200,
            'data' => false,
            'message' => 'Email and Password are required'
        ]);
    }
}