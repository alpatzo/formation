<?php

namespace App\Controller;
use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ArticleController extends AbstractController
{
    /**
     * @Route("/articles", name="articles")
     */
    public function articles(ArticleRepository $repository)
    {
        $articles= $repository->findAll();
        return $this->render("article/index.html.twig",['articles'=>$articles]);
    }
    /**
     * @Route("/articles/add", name="ajouterarticle")
     */
    public function new(Request $request)
    {  
        $articles =new Article();
        $form=$this->createForm(ArticleType::class,$articles);
        $form->handleRequest($request);
        if($form->isSubmitted()&& $form->isValid()){
            /** @var UploadedFile $imageFile */
            $imageFile=$form->get('image')->getData();
            if($imageFile){
                $origenalFilename = pathinfo($imageFile->getClientOriginalName(),PATHINFO_FILENAME);
                $safeFilename = $origenalFilename;
    // $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()',
    // $origenalFilename);

                $newFilename =$safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();
                try{
                    $imageFile->move($this->getParameter(('image'),$newFilename),$newFilename);

                }
                catch (FileException $e){

                }
                $articles->setImage($newFilename);
            }
                $articles=$form->getData();
                $entityManger= $this->getDoctrine()->getManager();
                $entityManger->persist($articles);
                $entityManger->flush();
                return $this->redirectToRoute('articles');

        }
       
            return $this->render('article/new.html.twig',['form'=>$form->createView()]);
        
           
    }
    /**
*@Route("/article/{id}",name="article_show")
*/
//Detail d'produit
public function show($id,Request $request){

    $articles =new Article();
        $form=$this->createForm(ArticleType::class,$articles);
        $form->handleRequest($request);
        if($form->isSubmitted()&& $form->isValid()){
            /** @var UploadedFile $imageFile */
            $imageFile=$form->get('image')->getData();
            if($imageFile){
                $origenalFilename = pathinfo($imageFile->getClientOriginalName(),PATHINFO_FILENAME);
                $safeFilename = $origenalFilename;
    // $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()',
    // $origenalFilename);

                $newFilename =$safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();
                try{
                    $imageFile->move($this->getParameter(('image'),$newFilename),$newFilename);

                }
                catch (FileException $e){

                }
                $articles->setImage($newFilename);
            }
        }
        $articles=$this->getDoctrine()->getRepository(Article::class)->find($id);

    return $this->render('article/detail.html.twig',array('form'=>$form->createView(),'articles'=>$articles));
    }
}
