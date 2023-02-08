<?php

namespace App\Controller;

use App\Repository\QuestionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
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

    /**
     * @Route("/question/{id}/vu",methods={"POST"},name="questionCheck")
     */
    public function questionVu(QuestionRepository $questionRepository,EntityManagerInterface $entityManager,$id=0)
    {
        if(!$this->getUser())
            return new JsonResponse("action non autorisée !!",Response::HTTP_FORBIDDEN);
        $question=$questionRepository->findOneBy([
            "id"=>$id
        ]);
        if(!$question)
            return new JsonResponse("question introuvable !",Response::HTTP_NOT_FOUND);
        $question->setVu(!$question->isVu());
        $entityManager->flush();
        return new JsonResponse("action bien effectuée",Response::HTTP_OK);


    }
}
