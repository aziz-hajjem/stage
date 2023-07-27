<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\FicheDePaieRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/users')]
class AdminUserController extends AbstractController
{
    #[Route('/', name: 'app_admin_user_index', methods: ['GET'])]
    public function index(UserRepository $userRepository , FicheDePaieRepository $ficheRep): Response
    {
        $user=$this->getUser();
        return $this->render('admin_user/index.html.twig', [
            'users' => $userRepository->findAll(),
            'user' => $user,
            'fiches'=>$ficheRep->findAll(),
        ]);
    }

    // #[Route('/new', name: 'app_admin_user_new', methods: ['GET', 'POST'])]
    // public function new(Request $request, EntityManagerInterface $entityManager): Response
    // {
    //     $user = new User();
    //     $form = $this->createForm(UserType::class, $user);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $entityManager->persist($user);
    //         $entityManager->flush();

    //         return $this->redirectToRoute('app_admin_user_index', [], Response::HTTP_SEE_OTHER);
    //     }

    //     return $this->renderForm('admin_user/new.html.twig', [
    //         'user' => $user,
    //         'form' => $form,
    //     ]);
    // }

    #[Route('/{id}', name: 'app_admin_user_show', methods: ['GET'])]
    public function show(User $emp): Response
    {
        $user=$this->getUser();
        return $this->render('admin_user/show.html.twig', [
            'user' => $user,
            'emp' => $emp
        ]);
    }

    // #[Route('/{id}/edit', name: 'app_admin_user_edit', methods: ['GET', 'POST'])]
    // public function edit(Request $request, User $user, EntityManagerInterface $entityManager): Response
    // {
    //     $form = $this->createForm(UserType::class, $user);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $entityManager->flush();

    //         return $this->redirectToRoute('app_admin_user_index', [], Response::HTTP_SEE_OTHER);
    //     }

    //     return $this->renderForm('admin_user/edit.html.twig', [
    //         'user' => $user,
    //         'form' => $form,
    //     ]);
    // }

    #[Route('/{id}', name: 'app_admin_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $emp, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$emp->getId(), $request->request->get('_token'))) {
            $entityManager->remove($emp);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_admin_user_index', [], Response::HTTP_SEE_OTHER);
    }
}
