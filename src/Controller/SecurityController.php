<?php

namespace App\Controller;

use App\Entity\Admin;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/admin/login", name="app_login")
     */
    public function index(AuthenticationUtils $authenticationUtils): Response
    {
        $error=$authenticationUtils->getLastAuthenticationError();
        $lastUserName=null;
        $lastUserName==$authenticationUtils->getLastUsername();
        return $this->render('security/login.html.twig', [
            'controller_name' => 'SecurityController',
            'lastUserName'=>$lastUserName,
            'error'=>$error
        ]);
    }
    /**
     * @Route ("/register",name="app_register")
     */
    public function register(UserPasswordHasherInterface $passwordHasher,EntityManagerInterface $entityManager)
    {
        $admin=new Admin();
        $passwordText="12345";
        $hashedPassword=$passwordHasher->hashPassword($admin,$passwordText);
        $admin->setPassword($hashedPassword);
        $admin->setEmail('rahhoutioualid@gmail.com');
        $entityManager->persist($admin);
        $entityManager->flush();
        return $this->redirectToRoute("app_login");
    }

    /**
     * @Route("logout",name="app_logout")
     */
    public function logout()
    {

    }
}
