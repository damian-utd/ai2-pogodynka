<?php

namespace App\Controller;

use App\Entity\Attributes;
use App\Form\AttributesType;
use App\Repository\AttributesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/attributes')]
final class AttributesController extends AbstractController
{
    #[Route(name: 'app_attributes_index', methods: ['GET'])]
    public function index(AttributesRepository $attributesRepository): Response
    {
        return $this->render('attributes/index.html.twig', [
            'attributes' => $attributesRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_attributes_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $attribute = new Attributes();
        $form = $this->createForm(AttributesType::class, $attribute, [
            'validation_groups' => ['create'],
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($attribute);
            $entityManager->flush();

            return $this->redirectToRoute('app_attributes_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('attributes/new.html.twig', [
            'attribute' => $attribute,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_attributes_show', methods: ['GET'])]
    public function show(Attributes $attribute): Response
    {
        return $this->render('attributes/show.html.twig', [
            'attribute' => $attribute,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_attributes_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Attributes $attribute, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AttributesType::class, $attribute, [
            'validation_groups' => ['edit'],
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_attributes_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('attributes/edit.html.twig', [
            'attribute' => $attribute,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_attributes_delete', methods: ['POST'])]
    public function delete(Request $request, Attributes $attribute, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$attribute->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($attribute);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_attributes_index', [], Response::HTTP_SEE_OTHER);
    }
}
