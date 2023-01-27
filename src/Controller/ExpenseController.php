<?php

namespace App\Controller;

use App\Entity\Expense;
use App\Form\ExpenseType;
use App\Repository\ExpenseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

//create route for expense

/**
 * @property ExpenseRepository $expenseRepository
 */
#[Route('/expense')]
class ExpenseController extends AbstractController
{
    #[Route('/expense', name: 'app_expense')]
    public function index(): Response
    {
        return $this->render('expense/index.html.twig', [
            'controller_name' => 'ExpenseController',
        ]);
    }

    //create function __construct
    public function __construct(ExpenseRepository $expenseRepository)
    {
        $this->expenseRepository = $expenseRepository;
    }

    //create route expense/new
    #[Route('/new', name: 'app_expense_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $expense = new Expense();
        $form = $this->createForm(ExpenseType::class, $expense);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($expense);
            $entityManager->flush();

            return $this->redirectToRoute('expense_index');
        }

        return $this->render('expense/new.html.twig', [
            'expense' => $expense,
            'form' => $form->createView(),
        ]);
    }

    //create route expense/{id}
    #[Route('/{id}', name: 'app_expense_show', methods: ['GET'])]
    public function show(Expense $expense): Response
    {
        return $this->render('expense/show.html.twig', [
            'expense' => $expense,
        ]);
    }

    //create route expense/{id}/edit
    #[Route('/{id}/edit', name: 'app_expense_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Expense $expense): Response
    {
        $form = $this->createForm(ExpenseType::class, $expense);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('expense_index');
        }

        return $this->render('expense/edit.html.twig', [
            'expense' => $expense,
            'form' => $form->createView(),
        ]);
    }

    //create function delete
    #[Route('/{id}', name: 'app_expense_delete', methods: ['POST'])]
    public function delete(Request $request, Expense $expense): Response
    {
        if ($this->isCsrfTokenValid('delete'.$expense->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($expense);
            $entityManager->flush();
        }

        return $this->redirectToRoute('expense_index');
    }


}
