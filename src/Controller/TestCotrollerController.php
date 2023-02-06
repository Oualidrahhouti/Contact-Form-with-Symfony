<?php

namespace App\Controller;

use App\Form\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestCotrollerController extends AbstractController
{
    /**
     * @Route("/", name="app_test_cotroller")
     */
    public function index(Request $request): Response
    {
        $contactForm=$this->createForm(ContactType::class);
        $contactForm->handleRequest($request);
        return $this->renderForm('test_cotroller/index.html.twig', [
            'controller_name' => 'TestCotrollerController',
            'contactForm'=>$contactForm
        ]);
    }
}
