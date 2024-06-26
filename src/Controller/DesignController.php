<?php

namespace App\Controller;

use App\Entity\Design;
use App\Form\DesignType;
use App\Repository\DesignRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/design')]
class DesignController extends AbstractController
{
    #[Route('/', name: 'app_design_index', methods: ['GET'])]
    public function index(DesignRepository $designRepository): Response
    {
        return $this->render('design/index.html.twig', [
            'designs' => $designRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_design_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $design = new Design();
        $form = $this->createForm(DesignType::class, $design);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($design);
            $entityManager->flush();

            return $this->redirectToRoute('app_design_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('design/new.html.twig', [
            'design' => $design,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_design_show', methods: ['GET'])]
    public function show(Design $design): Response
    {
        return $this->render('design/show.html.twig', [
            'design' => $design,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_design_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Design $design, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(DesignType::class, $design);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_design_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('design/edit.html.twig', [
            'design' => $design,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_design_delete', methods: ['POST'])]
    public function delete(Request $request, Design $design, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$design->getId(), $request->request->get('_token'))) {
            $entityManager->remove($design);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_design_index', [], Response::HTTP_SEE_OTHER);
    }
}
