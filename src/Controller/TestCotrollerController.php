<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestCotrollerController extends AbstractController
{
    /**
     * @Route("/", name="app_test_cotroller")
     */
    public function index(): Response
    {
        return $this->render('test_cotroller/index.html.twig', [
            'controller_name' => 'TestCotrollerController',
        ]);
    }
}
