<?php

namespace App\Controller;

use App\Entity\FicheDePaie;
use App\Form\FicheDePaieType;
use App\Repository\FicheDePaieRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/fichedepaie')]
class AdminFichedepaieController extends AbstractController
{
    #[Route('/', name: 'app_admin_fichedepaie_index', methods: ['GET'])]
    public function index(FicheDePaieRepository $ficheDePaieRepository): Response
    {
        $user=$this->getUser();
        return $this->render('admin_fichedepaie/index.html.twig', [
            'fiche_de_paies' => $ficheDePaieRepository->findAll(),
            'user'=>$user
        ]);
    }

    #[Route('/new/{id}', name: 'app_admin_fichedepaie_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, $id, UserRepository $userRep): Response
    {
        $user=$this->getUser();
        $emp = $userRep->find($id);
        $ficheDePaie = new FicheDePaie();
        $form = $this->createForm(FicheDePaieType::class, $ficheDePaie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ficheDePaie->setUser($emp);
            $entityManager->persist($ficheDePaie);
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin_fichedepaie/new.html.twig', [
            'fiche_de_paie' => $ficheDePaie,
            'form' => $form,
            'user' => $user
        ]);
    }

    // #[Route('/{idUser}', name: 'app_admin_fichedepaie_show', methods: ['GET'])]
    // public function show(FicheDePaie $ficheDePaie,FicheDePaieRepository $ficheRep,UserRepository $userRep ,$idUser): Response
    // {
    //     $user=$userRep->find($idUser);

    //     if (!$user) {
    //         // Handle the case where the user with the given ID is not found.
    //         throw $this->createNotFoundException('User not found');
    //     }
    //     $fiche=$ficheRep->findOneBy(['user'=>$user]);
    //     return $this->render('admin_fichedepaie/show.html.twig', [
    //         'fiche_de_paie' => $fiche,
    //     ]);
    // }
    #[Route('/{id}', name: 'app_admin_fichedepaie_show', methods: ['GET'])]
    public function show(UserRepository $userRep, $id): Response
    {
        $user=$this->getUser();
        $emp = $userRep->find($id);

        if (!$emp) {
            throw $this->createNotFoundException('User not found');
        }

        $ficheRep = $this->getDoctrine()->getRepository(FicheDePaie::class);
        $fiche = $ficheRep->findOneBy(['user' => $emp]);

        if (!$fiche) {
            throw $this->createNotFoundException('fiche not found');
        }

        return $this->render('admin_fichedepaie/show.html.twig', [
            'fiche' => $fiche,
            'emp'=>$emp,
            'user' => $user
            
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_fichedepaie_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, EntityManagerInterface $entityManager,UserRepository $userRep, $id): Response
    {
        $user=$this->getUser();
        $emp = $userRep->find($id);

        if (!$emp) {
            throw $this->createNotFoundException('User not found');
        }
        $ficheRep = $this->getDoctrine()->getRepository(FicheDePaie::class);
        $ficheDePaie = $ficheRep->findOneBy(['user' => $emp]);
        if (!$ficheDePaie) {
            throw $this->createNotFoundException('fiche not found');
        }
        $form = $this->createForm(FicheDePaieType::class, $ficheDePaie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin_fichedepaie/edit.html.twig', [
            'fiche_de_paie' => $ficheDePaie,
            'form' => $form,
            'user'=>$user
        ]);
    }

    #[Route('/{id}', name: 'app_admin_fichedepaie_delete', methods: ['POST'])]
    public function delete(Request $request, FicheDePaie $ficheDePaie, EntityManagerInterface $entityManager): Response
    {
        
        if ($this->isCsrfTokenValid('delete' . $ficheDePaie->getId(), $request->request->get('_token'))) {
            $entityManager->remove($ficheDePaie);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_admin_fichedepaie_index', [], Response::HTTP_SEE_OTHER);
    }
}
