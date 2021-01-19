<?php

namespace App\Controller;

use App\Entity\Topic;
use App\Form\TopicType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    /**
     * @Route("/home", name="home")
     * @IsGranted("ROLE_USER")
     */
    public function index(Request $request): Response
    {
        $topic = new Topic();
        $topics = $this->getDoctrine()->getRepository(Topic::class)->getAll();
        $form = $this->createForm(TopicType::class, $topic);
        $form->remove('cancel');

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            
            $topic = $form->getData();
            $topic->setAuthor($this->getUser());
           
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($topic);
            $entityManager->flush();

            return $this->redirectToRoute('home');
        }
     
        return $this->render('home/index.html.twig', [
            "topics" => $topics,
            "form_addTopic" => $form->createView()
        ]);
    }
}
