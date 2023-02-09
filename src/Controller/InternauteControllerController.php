<?php

namespace App\Controller;

use App\Entity\Internaute;
use App\Entity\Question;
use App\Form\ContactType;
use App\Repository\InternauteRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class InternauteControllerController extends AbstractController
{
    /**
     * this route is used for the contact-us form
     * @Route("/", name="app_test_cotroller")
     */
    public function index(Request $request,InternauteRepository $internauteRepository,
                          EntityManagerInterface $entityManager,Filesystem $filesystem): Response
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
            //if the passed email is unfound, we create a new client
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
            $data=[
                "nom"=>$internaut->getNom(),
                "email"=>$internaut->getEmail(),
                "question"=>$questionText,
                "date"=>new DateTime()
            ];
            //store the question informations in a json file
            $rootDirectory=$this->getParameter("kernel.project_dir");
            $destination=$rootDirectory.$this->getParameter("app.questions_files");
            $filename="question_".uniqid().".json";
            $fileFullName=$destination.$filename;
            $filesystem->dumpFile($fileFullName, json_encode($data));
            $successMessage="Question bien envoyée !";
        }
        return $this->renderForm('internaute/index.html.twig', [
            'contactForm'=>$contactForm,
            "successMessage"=>$successMessage
        ]);
    }




}
