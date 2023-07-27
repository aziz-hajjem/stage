<?php

namespace App\Controller;

use App\Entity\Formation;
use App\Entity\Participation;
use App\Entity\Produit;
use App\Form\FormationFormType;
use App\Form\ProduitFormType;
use App\Form\UserFormType;
use App\Repository\CongeRepository;
use App\Repository\FormationRepository;
use App\Repository\ProduitRepository;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route('/admin/main', name: 'app_admin')]
    public function index(): Response
    {
        $user = $this->getUser();
        return $this->render('admin.html.twig', [
            'user' => $user,

        ]);
    }
    #[Route('/admin/profile/edit', name: 'app_admin_profile_edit')]
    public function edit_profile(ManagerRegistry $doctrine, Request $request): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(UserFormType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $em = $doctrine->getManager();
            $imageFile = $form->get('image')->getData();

            if ($imageFile) {
                $imagesDirectory = 'C:/xampp/htdocs/images';
                $originalFilename = $imageFile->getClientOriginalName();
                $filenameWithoutSpaces = str_replace(' ', '_', $originalFilename);

                try {
                    $imageFile->move($imagesDirectory, $filenameWithoutSpaces);
                } catch (FileException $e) {
                    // Handle exception
                }

                $user->setImage($filenameWithoutSpaces);
            }

            $em->flush();

            return $this->redirectToRoute('app_admin');
        }

        return $this->render('admin_user/edit.html.twig', [
            'user' => $user,
            'f' => $form->createView(),
        ]);
    }
    #[Route('/admin/conge', name: 'app_admin_conge')]
    public function allAdminConge(CongeRepository $congeRepository): Response
    {

        $user = $this->getUser();
        return $this->render('admin_user/conge.html.twig', [
            'user' => $user,
            'conges' => $congeRepository->findAll()

        ]);
    }
    #[Route('/admin/congeValidate/{id}', name: 'admin_validate_Conge')]
    public function validateConge($id, CongeRepository $congeRep, ManagerRegistry $doctrine): Response
    {
        // methide de revuperation finAll() find($id) findBy()
        $conge = $congeRep->find($id);

        // Action supprision
        // methode de persistance : persist() remove() flush()
        $em = $doctrine->getManager();
        $congeRep->validateConge($conge);
        // flush heya el commit
        $em->flush();
        return $this->redirectToRoute('app_admin_conge');
    }
    #[Route('/admin/congeNoValidate/{id}', name: 'admin_NoValidate_Conge')]
    public function NoValidateConge($id, CongeRepository $congeRep, ManagerRegistry $doctrine): Response
    {
        // methide de revuperation finAll() find($id) findBy()
        $conge = $congeRep->find($id);

        // Action supprision
        // methode de persistance : persist() remove() flush()
        $em = $doctrine->getManager();
        $congeRep->NoValidateConge($conge);
        // flush heya el commit
        $em->flush();
        return $this->redirectToRoute('app_admin_conge');
    }
    #[Route('/admin/holidays', name: 'app_admin_holidays')]
    public function holidays(): Response
    {
        $user = $this->getUser();
        return $this->render('holidays.html.twig', [
            'user' => $user,

        ]);
    }
    //ajouterformation
    #[Route('/admin/ajoutforma', name: 'ajoutforma')]
    public function ajoutforma(ManagerRegistry $doctrine, Request $request)
    {
        $user=$this->getUser();
        $Formation = new Formation();
        $form = $this->createForm(FormationFormType::class, $Formation);
        $form->handleRequest($request);
        if ($form->isSubmitted()&&$form->isValid()) {
            $em = $doctrine->getManager();
            $imageFile = $form->get('image')->getData();

            if ($imageFile) {
                $imagesDirectory = 'C:/xampp/htdocs/images';
                $originalFilename = $imageFile->getClientOriginalName();
                $filenameWithoutSpaces = str_replace(' ', '_', $originalFilename);

                try {

                    $imageFile->move($imagesDirectory, $filenameWithoutSpaces);
                } catch (FileException $e) {
                    // Handle exception
                }
                $Formation->setImage($filenameWithoutSpaces);
            }

            $em->persist($Formation);
            $em->flush();
            return $this->redirectToRoute("app_admin");
        }
        return $this->renderForm(
            "admin_formation/addformation.html.twig",
            array("f" => $form,'user' => $user)
        );
    }

    //Afficher formation
    #[Route('/admin/afficheforma', name: 'afficheforma')]
    public function afficheforma(FormationRepository $Rep): Response
    {
        $user=$this->getUser();
        $Formation = $Rep->orderById();


        return $this->render('admin_formation/afficheformation.html.twig', [
            'f' => $Formation,
            'user' => $user,


        ]);
    }

    //Supprimer formation
    #[Route('/admin/suppforma/{id}', name: 'suppforma')]
    public function suppforma($id, FormationRepository $r, ManagerRegistry $doctrine): Response
    {   //recuperer le produit a supprimer
        $user=$this->getUser();
        $Formation = $r->find($id);
        //action supprimer
        $em = $doctrine->getManager();
        $em->remove($Formation);
        $em->flush();
        return $this->redirectToRoute('afficheforma',);
    }
    //modifier formation
    #[Route('/admin/modifforma/{id}', name: 'modifforma')]
    public function modifforma(ManagerRegistry $doctrine, Request $request, $id, FormationRepository $r)
    { { //récupérer la formation a modifier
            $Formation = $r->find($id);
            $user=$this->getUser();
            $form = $this->createForm(FormationFormType::class, $Formation);
            $form->handleRequest($request);
            if ($form->isSubmitted()&&$form->isValid()) {
                $em = $doctrine->getManager();
                $imageFile = $form->get('image')->getData();

                if ($imageFile) {
                    $imagesDirectory = 'C:/xampp/htdocs/images';
                    $originalFilename = $imageFile->getClientOriginalName();
                    $filenameWithoutSpaces = str_replace(' ', '_', $originalFilename);

                    try {

                        $imageFile->move($imagesDirectory, $filenameWithoutSpaces);
                    } catch (FileException $e) {
                        // Handle exception
                    }
                    $Formation->setImage($filenameWithoutSpaces);
                }



                $em->persist($Formation);
                $em->flush();
                return $this->redirectToRoute('afficheforma');
            }

            return $this->renderForm(
                "admin_formation/updateFormation.html.twig",
                array("f" => $form,"user" => $user)
            );
        }
    }
    /////participation
   //ajouter particicpation
  
  
   #[Route('/admin/ajoutpartici/{user_id}/{formation_id}', name: 'ajoutpartici')]
   public function ajoutpartici(int $user_id, int $formation_id, UserRepository $userRepository, FormationRepository $formationRepository, ManagerRegistry $doctrine, Request $request): Response
   {
       $formation = $formationRepository->find($formation_id);
       $user = $this->getUser();
   
       if ($formation && $user) {
           $entityManager = $doctrine->getManager();
           $participation = new Participation();
           $participation->setUser($user);
           $participation->setFormation($formation);
           $entityManager->persist($participation);
           $entityManager->flush();
       }
       return $this->redirectToRoute('app_admin');
       return $this->render('admin_formation/afficheformation.html.twig', [
           'f' => $formation,
           'user' => $user,
       ]);}
   
       //afficherAllparticipation
       
     /*#[Route('/afficheparti', name: 'afficheparti')]
     public function afficheparti(ParticipationRepository $Rep): Response
     { $participation=$Rep->orderById();
        
     
         return $this->render('afficheparticipant.html.twig', [
         'f'=>$participation  ,
         
   
         ]);}*/
   
   
         #[Route('/admin/afficheparti/{id_formation}', name: 'afficheparti')]
         public function afficheparti(int $id_formation): Response
         {
            $user=$this->getUser();
             $participation = $this->getDoctrine()->getRepository(Participation::class)->findBy(['formation' => $id_formation]);
         
             return $this->render('admin_formation/afficheparticipant.html.twig', [
                 'f' => $participation,
                 'user' => $user
             ]);
         }
   
         //ajouterproduit
         #[Route('/admin/ajoutprod', name: 'ajoutprod')]
         public function ajoutprod(ManagerRegistry $doctrine,Request $request)
                 {
                    $user=$this->getUser();
                 $Produit= new Produit();
                 $form=$this->createForm(ProduitFormType::class,$Produit);
                     $form->handleRequest($request);
                     if($form->isSubmitted() ){
                         $em =$doctrine->getManager() ;
                         $imageFile = $form->get('image')->getData();
                         
                         if ($imageFile) {
                             $imagesDirectory = 'C:/xampp/htdocs/images';
                             $originalFilename = $imageFile->getClientOriginalName();
                             $filenameWithoutSpaces = str_replace(' ', '_', $originalFilename);
             
                             try {
                                 
                                 $imageFile->move($imagesDirectory,$filenameWithoutSpaces);
                                 
                             } catch (FileException $e) {
                                 // Handle exception
                             }
                             $Produit->setImage($filenameWithoutSpaces);
                         }
                         
                         $em->persist($Produit);
                         $em->flush();
                         return $this->redirectToRoute("afficheprod");}
                 return $this->renderForm("admin_formation/addProduit.html.twig",
                         array("f"=>$form,"user"=>$user));
                     }
   
                      //Afficher Produit
     #[Route('/admin/afficheprod', name: 'afficheprod')]
     public function afficheprod(ProduitRepository $Rep): Response
     { $Produit=$Rep->orderById();
        
        $user=$this->getUser();
         return $this->render('admin_formation/afficheproduit.html.twig', [
         'p'=>$Produit  ,
         'user'=>$user
         
   
         ]);
     }
   
      //Supprimer Produit
      #[Route('/admin/suppprod/{id}', name: 'suppprod')]
      public function suppprod($id,ProduitRepository $r, ManagerRegistry $doctrine): Response
      {   //recuperer le produit a supprimer
        
          $Produit=$r->find($id);
          //action supprimer
          $em=$doctrine->getManager();
          $em->remove($Produit);
          $em->flush();
          return $this->redirectToRoute('afficheprod',); 
      }  

       //modifier produit
#[Route('/admin/modifprod/{id}', name: 'modifprod')]
public function modifprod(ManagerRegistry $doctrine,Request $request,$id,ProduitRepository $r)
                       {
      { //récupérer le produit a modifier
        $Produit=$r->find($id);
        $user=$this->getUser();
    $form=$this->createForm(ProduitFormType::class,$Produit);
     $form->handleRequest($request);
     if($form->isSubmitted()  ){
    $em =$doctrine->getManager() ;
    $imageFile = $form->get('image')->getData();
       
    if ($imageFile) {
        $imagesDirectory = 'C:/xampp/htdocs/images';
        $originalFilename = $imageFile->getClientOriginalName();
        $filenameWithoutSpaces = str_replace(' ', '_', $originalFilename);

        try {
            
            $imageFile->move($imagesDirectory,$filenameWithoutSpaces);
            
        } catch (FileException $e) {
            // Handle exception
        }
        $Produit->setImage($filenameWithoutSpaces);
    }
    

 
    $em->persist($Produit);
    $em->flush();
    return $this->redirectToRoute('afficheprod');}

   return $this->renderForm("admin_formation/addProduit.html.twig",
    array("f"=>$form,'user'=>$user));
}}
}
