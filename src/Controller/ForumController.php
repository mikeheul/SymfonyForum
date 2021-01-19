<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\Topic;
use App\Form\PostType;
use App\Form\TopicType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @IsGranted("ROLE_USER")
 */
class ForumController extends AbstractController
{

    // * GESTION DES TOPICS
    /**
     * @Route("topic/add", name="topic_add")
     */
    public function addTopic(Request $request, Topic $topic = null) {

        $topic = new Topic();
        $form = $this->createForm(TopicType::class, $topic);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            
            $topic = $form->getData();
            $topic->setAuthor($this->getUser());
           
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($topic);
            $entityManager->flush();

            return $this->redirectToRoute('home');
        }

        return $this->render("forum/topic_add.html.twig", [
            "topic" => $topic,
            "form_addTopic" => $form->createView()
        ]);
    }

    /**
     * @Route("/topic/{id}/delete", name="topic_delete")
     */
    public function deleteTopic(Topic $topic = null) {

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($topic);
        $entityManager->flush();
        return $this->redirectToRoute('home');
    }

    /**
     * @Route("/topic/close/{id}", name="topic_close")
     */
    public function closeTopic(Topic $topic): Response {

        $topic->setClosed($topic->getClosed() ? false : true);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->flush();

        return $this->redirectToRoute('home');

        // return new JsonResponse($topic->getClosed());

        /* 
            $encoders = [new XmlEncoder(), new JsonEncoder()];
            $normolizers = [new ObjectNormalizer()],
            $serializer = new Serializer($normalizers, $encoders),
            $json = $serializer->serialize($topic, 'json')

            return new JsonResponse($json); 

        */
    }

    /**
     * @Route("/topic/solve/{id}", name="topic_solve")
     */
    public function solveTopic(Topic $topic): Response {

        $topic->setSolved(true);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->flush();

        return $this->redirectToRoute('home');
    }

    /**
     * @Route("/topic/unsolve/{id}", name="topic_unsolve")
     */
    public function unsolveTopic(Topic $topic): Response {

        $topic->setSolved(false);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->flush();

        return $this->redirectToRoute('home');
    }

    // * GESTION DES POSTS
    /**
     * @Route("/topic/{id}", name="posts")
     */
    public function posts(Request $request, Topic $topic) {

        $post = new Post();
        $form = $this->createForm(PostType::class, $post);

        $form->remove('cancel');
        
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $post = $form->getData();
                
            $post->setAuthor($this->getUser());
            $post->setTopic($topic);
    
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($post);
            $entityManager->flush();
    
            return $this->redirectToRoute('posts', ['id' => $topic->getId()]);
        }

        return $this->render("forum/posts.html.twig", [
            "topic" => $topic,
            "form_addPost" => $form->createView()
        ]);
    }

    /**
     * @Route("/post/{id}/delete", name="post_delete")
     */
    public function deletePost(Post $post) {

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($post);
        $entityManager->flush();
        return $this->redirectToRoute('posts', ['id' => $post->getTopic()->getId()]);
    }

    /**
     * @Route("/post/{id}", name="post_edit")
     */
    public function editPost(Request $request, Post $post) {

        $form = $this->createForm(PostType::class, $post);
        
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $post = $form->getData();
            
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($post);
            $entityManager->flush();
    
            return $this->redirectToRoute('posts', ['id' => $post->getTopic()->getId()]);
        }

        if($this->getUser() === $post->getAuthor()){
            return $this->render("forum/post_edit.html.twig", [
                "post" => $post,
                "form_editPost" => $form->createView()
            ]);
        } else {
            return $this->redirectToRoute("home");
        }
    }
}
