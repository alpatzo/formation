<?php

namespace App\Controller;
use App\Entity\Job;
use App\Entity\Article;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/home", name="home")
     */
    public function index()
    {   
        
        $jobs= $this->getDoctrine()->getRepository(Job::class)->findAll();
        $article= $this->getDoctrine()->getRepository(Article::class)->findAll();

     return $this->render('home/index.html.twig',['jobs'=> $jobs,'article'=>$article,]);  
        } 
}
