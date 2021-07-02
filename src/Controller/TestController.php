<?php

namespace App\Controller;

use App\Form\JobType;
use App\Entity\Job;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\JobRepository;


class TestController extends AbstractController
{
    
    /**
     * @Route("/helucky")
     */
    public function aff(): Response
    {
        return $this->render('lucky/affiche.html.twig');
    }
    /**
     * @Route("/numero")
     */
    public function number(): Response
    {
        $number = random_int(0, 10);

        return $this->render('lucky/number.html.twig', [
            'num' => $number,
        ]);
    }
    /**
     * @Route("/hello", name="hi")
     */
    public function hello()
    {
        return new Response("hello world");
    }
    /**
     * @Route("/aa")
     */
    public function aa()
    {
        return $this->redirectToRoute("hi");
    }
    /**
     * @Route("/test/{id}", name="test")
     */
    public function index($id)
    {   
        return new Response("affichage de id: ".$id);
    }
    /**
     * @Route("/save")
     */
    public function save()
    {
        $entityManager = $this->getDoctrine()->getManager();
        $job = new Job();
        $job -> setCategory('informatique');
        $job ->setSousCategory('developpent_web');
        $entityManager->persist($job);
        $entityManager->flush();
        return new Response ('save this job '.$job->getId());
    }
    /**
     * @Route("/job", name="joblist")
     */
    public function jobs(JobRepository $repository)
    {
        $jobs= $repository->findAll();
        return $this->render("job/index.html.twig",['jobs'=>$jobs]);
    }
    /**
     * @Route("/job/{id}", name="jobbyid")
     */
    public function jobbyid($id)
    {
        $job= $this->getDoctrine()->getRepository(job::class)->find($id);
        return $this->render("job/category.html.twig",['job'=>$job]);
    }
    /**
     * @Route("/suppjob/{id}", name="suppjob")
     */
    public function suppjob($id)
    {
        $job= $this->getDoctrine()->getRepository(job::class)->find($id);
        return $this->render("job/supprimer.html.twig",['job'=>$job]);
    }
    /**
     * @Route("/jobs/new", name="new")
     * Method({"GET", "POST"})
     */
  
    public function new(Request $request)
    {  
        $job= new Job();
        $form = $this->createForm(JobType::class,$job); 
        $form->handleRequest($request); 
        if($form->isSubmitted() && $form->isValid()) {    
            $job=$form->getData();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($job); $entityManager->flush();
            return $this->redirectToRoute('joblist');
    }     
            return $this->render('job/new.html.twig',['form'=>$form->createView()]);
              
    }
    /**
     * @Route("/jobs/edit/{id}", name="modifier")
     * Method({"GET", "POST"})
     */
    public function modifier(Request $request,$id){
        $job= new Job();
        $job= $this->getDoctrine()->getRepository(job::class)->find($id);
        $form = $this->createForm(JobType::class,$job); 
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) { 
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();
            return $this->redirectToRoute('joblist');
        }
        return $this->render('job/modifier.html.twig',['form'=>$form->createView()]);
    }
    /**
     * @Route("/jobs/supp/{id}", name="supprimer")
     * Method({"GET", "POST"})
     */
    public function supprimerjob($id){
        $job= new Job();
        $entityManager = $this->getDoctrine()->getManager();
        $supp= $entityManager->getRepository(job::class)->find($id);
        $entityManager->remove($supp);
        $entityManager->flush();
        return $this->redirectToRoute('joblist');
    }
    
}