<?php

namespace App\Controller;

use App\Repository\QuestionRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @IsGranted("ROLE_ADMIN")
 * @Route("/admin")
 */
class AdminController extends AbstractController
{
    /**
     * @Route("",name="adminHome")
     */
    public function admin(QuestionRepository $questionRepository):Response
    {
        $questions=$questionRepository->findAllJoinInternaute();

        return $this->render("admin/admin_dashboard.html.twig",[
            "questions"=>$questions,
            "admin"=>$this->getUser()
        ]);
    }
    /**
     * @Route("/internaute/{id}",name="adminInternauteShow")
     */
    public function adminShowInternaute(QuestionRepository $questionRepository,$id):Response
    {
        $questions=$questionRepository->findByInternaute($id);
        return $this->render("admin/admin_internaute_show.html.twig",[
            "questions"=>$questions,
            "admin"=>$this->getUser()
        ]);
    }
}
