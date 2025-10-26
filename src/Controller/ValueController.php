<?php

namespace App\Controller;

use App\Entity\Value;
use App\Form\ValueType;
use App\Repository\ValueRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/value')]
final class ValueController extends AbstractController
{
    #[Route('/', name: 'app_value_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $query = $entityManager->createQueryBuilder()
            ->select('v', 'm', 'l', 'a')
            ->from(Value::class, 'v')
            ->join('v.measurement', 'm')
            ->join('m.location', 'l')
            ->join('v.attribute', 'a')
            ->getQuery();

        $values = $query->getResult();

        return $this->render('value/index.html.twig', [
            'values' => $values,
        ]);
    }


    #[Route('/new', name: 'app_value_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $value = new Value();
        $form = $this->createForm(ValueType::class, $value, [
            'validation_groups' => ['create'],
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($value);
            $entityManager->flush();

            return $this->redirectToRoute('app_value_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('value/new.html.twig', [
            'value' => $value,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_value_show', methods: ['GET'])]
    public function show(Value $value): Response
    {
        return $this->render('value/show.html.twig', [
            'value' => $value,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_value_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Value $value, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ValueType::class, $value, [
            'validation_groups' => ['edit'],
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_value_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('value/edit.html.twig', [
            'value' => $value,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_value_delete', methods: ['POST'])]
    public function delete(Request $request, Value $value, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$value->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($value);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_value_index', [], Response::HTTP_SEE_OTHER);
    }
}
