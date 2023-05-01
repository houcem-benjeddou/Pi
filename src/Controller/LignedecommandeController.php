<?php

namespace App\Controller;

use App\Entity\Lignedecommande;
use App\Form\LignedecommandeType;
use App\Repository\LignedecommandeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/lignedecommande')]
class LignedecommandeController extends AbstractController
{
    #[Route('/', name: 'app_lignedecommande_index', methods: ['GET'])]
    public function index(LignedecommandeRepository $lignedecommandeRepository): Response
    {
        return $this->render('lignedecommande/index.html.twig', [
            'lignedecommandes' => $lignedecommandeRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_lignedecommande_new', methods: ['GET', 'POST'])]
    public function new(Request $request, LignedecommandeRepository $lignedecommandeRepository): Response
    {
        $lignedecommande = new Lignedecommande();
        $form = $this->createForm(LignedecommandeType::class, $lignedecommande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $lignedecommandeRepository->save($lignedecommande, true);

            return $this->redirectToRoute('app_lignedecommande_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('lignedecommande/new.html.twig', [
            'lignedecommande' => $lignedecommande,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_lignedecommande_show', methods: ['GET'])]
    public function show(Lignedecommande $lignedecommande): Response
    {
        return $this->render('lignedecommande/show.html.twig', [
            'lignedecommande' => $lignedecommande,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_lignedecommande_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Lignedecommande $lignedecommande, LignedecommandeRepository $lignedecommandeRepository): Response
    {
        $form = $this->createForm(LignedecommandeType::class, $lignedecommande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $lignedecommandeRepository->save($lignedecommande, true);

            return $this->redirectToRoute('app_lignedecommande_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('lignedecommande/edit.html.twig', [
            'lignedecommande' => $lignedecommande,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_lignedecommande_delete', methods: ['POST'])]
    public function delete(Request $request, Lignedecommande $lignedecommande, LignedecommandeRepository $lignedecommandeRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$lignedecommande->getId(), $request->request->get('_token'))) {
            $lignedecommandeRepository->remove($lignedecommande, true);
        }

        return $this->redirectToRoute('app_lignedecommande_index', [], Response::HTTP_SEE_OTHER);
    }
}
