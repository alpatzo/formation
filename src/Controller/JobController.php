<?php

namespace App\Controller;

use App\Form\JobType;
use App\Entity\Job;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\JobRepository;

class JobController extends AbstractController
{
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
        return new Response ('save this job '.$job->getId().' de categorie '.$job->getCategory());
    }
    /**
     * @Route("/job", name="job")
     */
    public function jobs(JobRepository $repository)
    {
        $jobs= $repository->findAll();
        return $this->render("job/index.html.twig",['job'=>$jobs]);
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
