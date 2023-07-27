<?php

namespace App\Controller;

use App\Entity\FicheDePaie;
use App\Entity\Participation;
use App\Form\UserFormType;
use App\Repository\FormationRepository;
use App\Repository\ParticipationRepository;
use App\Repository\ProduitRepository;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index( ): Response
    {
        $user=$this->getUser();
       

        return $this->render('home/index.html.twig', [
            'user' => $user,
            
        ]);
    }
    #[Route('/home/profile', name: 'app_profile_show')]
    public function show_profile(): Response
    {
        $user=$this->getUser();
        return $this->render('home/index.html.twig', [
            'user' => $user,
        ]);
    }
    #[Route('/home/profile/edit', name: 'app_profile_edit')]
    public function edit_profile(ManagerRegistry $doctrine, Request $request): Response
    {
        $user=$this->getUser();
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

            return $this->redirectToRoute('app_home');
        }

        return $this->render('home/edit.html.twig', [
            'user' => $user,
            'f'=>$form->createView(),
        ]);
    }
    #[Route('/home/FicheDePaie', name: 'app_user_fichedepaie_show', methods: ['GET'])]
    public function show(): Response
    {
        $user=$this->getUser();

        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }

        $ficheRep = $this->getDoctrine()->getRepository(FicheDePaie::class);
        $fiche = $ficheRep->findOneBy(['user' => $user]);

        if (!$fiche) {
            return $this->render('home/NotFound.html.twig', [
                'user' => $user
                
            ]);
        }

        return $this->render('home/showFiche.html.twig', [
            'fiche' => $fiche,
            'user' => $user
            
        ]);
    }
    #[Route('/home/holidays', name: 'app_home_holidays')]
    public function show_holidays(): Response
    {
        $user=$this->getUser();
        return $this->render('home/holidays.html.twig', [
            'user' => $user,
        ]);
    }
    
     //Afficher formation
     #[Route('/home/afficheforma', name: 'user_afficheforma')]
     public function afficheforma(FormationRepository $Rep,ParticipationRepository $part): Response
     {
         $user=$this->getUser();
         $participation=$part->findAll();
         $Formation = $Rep->orderById();
 
 
         return $this->render('home/afficheformation.html.twig', [
             'f' => $Formation,
             'user' => $user,
             'p'=>$participation
 
 
         ]);
     }
 
     
     /////participation
    //ajouter particicpation
   
   
    #[Route('/home/ajoutpartici/{user_id}/{formation_id}', name: 'user_ajoutpartici')]
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
        return $this->redirectToRoute('user_afficheforma');
        return $this->render('home/afficheformation.html.twig', [
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
    
    
          #[Route('/home/afficheparti/{id_formation}', name: 'user_afficheparti')]
          public function afficheparti(int $id_formation): Response
          {
             $user=$this->getUser();
              $participation = $this->getDoctrine()->getRepository(Participation::class)->findBy(['formation' => $id_formation]);
          
              return $this->render('home/afficheParticipant.html.twig', [
                  'f' => $participation,
                  'user' => $user
              ]);
          }
    
          
    
                       //Afficher Produit
      #[Route('/home/afficheprod', name: 'user_afficheprod')]
      public function afficheprod(ProduitRepository $Rep): Response
      { $Produit=$Rep->orderById();
         
         $user=$this->getUser();
          return $this->render('home/afficheProduit.html.twig', [
          'p'=>$Produit  ,
          'user'=>$user
          
    
          ]);
      }
    
       //Supprimer Produit
      
}
