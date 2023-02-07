<?php

namespace App\Controller;

use App\Entity\Internaute;
use App\Entity\Question;
use App\Form\ContactType;
use App\Repository\InternauteRepository;
use App\Repository\QuestionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestCotrollerController extends AbstractController
{
    /**
     * @Route("/", name="app_test_cotroller")
     */
    public function index(Request $request,InternauteRepository $internauteRepository,EntityManagerInterface $entityManager): Response
    {
        $successMessage=null;
        $contactForm=$this->createForm(ContactType::class);
        $contactForm->handleRequest($request);
        if($contactForm->isSubmitted() && $contactForm->isValid())
        {
            $email=$contactForm->getData()["Internaute"]->getEmail();
            $internaut=$internauteRepository->findOneBy([
                'email'=>$email
            ]);
            if(!$internaut)
            {
                $nom=$contactForm->getData()["Internaute"]->getNom();
                $internaut=new Internaute();
                $internaut->setEmail($email);
                $internaut->setNom($nom);
                $entityManager->persist($internaut);
            }
            $questionText=$contactForm->getData()["Question"]->getQuestion();
            $question=new Question();
            $question->setQuestion($questionText);
            $question->setVu(false);
            $internaut->addQuestion($question);
            $entityManager->persist($question);
            $entityManager->flush();
            $successMessage="Question bien envoyée !";
        }
        return $this->renderForm('test_cotroller/index.html.twig', [
            'contactForm'=>$contactForm,
            "successMessage"=>$successMessage
        ]);
    }

    /**
     * @Route("/admin",name="adminHome")
     */
    public function admin(QuestionRepository $questionRepository):Response
    {
        $questions=$questionRepository->findAllJoinInternaute();

        return $this->render("admin/admin_dashboard.html.twig",[
            "questions"=>$questions
        ]);
    }
    /**
     * @Route("/admin/internaute/{id}",name="adminInternauteShow")
     */
    public function adminShowInternaute(QuestionRepository $questionRepository,$id):Response
    {
        $questions=$questionRepository->findByInternaute($id);

        return $this->render("admin/admin_internaute_show.html.twig",[
            "questions"=>$questions
        ]);
    }

}
