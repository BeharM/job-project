<?php

namespace App\Controller\Api;

use App\Entity\User;
use App\Entity\Zone;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api', name: 'api_')]
class RegistrationController extends AbstractController
{
    #[Route('/register', methods: ['POST'])]
    public function index(ManagerRegistry $doctrine, Request $request, UserPasswordHasherInterface $passwordHasher): Response
    {
        $em = $doctrine->getManager();
        $email = $request->get('email');
        $password = $request->get('password');
        $username = $request->get('username');
        $zoneId = $request->get('zone');
        //toDO
        //$roleId = $request->get('role');
        $zoneRepository = $em->getRepository(Zone::class);
        if ($email and $password) {
            $user = new User();
            $hashedPassword = $passwordHasher->hashPassword(
                $user,
                $password
            );
            if ($zoneId){
                $zone = $zoneRepository->find($zoneId);
            }else{
                $zone = $zoneRepository->getDefaultZone();
            }
            $user->setPassword($hashedPassword);
            $user->setEmail($email);
            $user->setUsername($username);
            $user->setZone($zone);

            $user->setCreatedAt(new \DateTimeImmutable('now'));
            $em->persist($user);
            $em->flush();

            return $this->json(['message' => 'Registered Successfully']);
        }

        return $this->json(['message' => 'Email and Password are required']);
    }
}