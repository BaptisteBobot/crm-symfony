<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RevisionController extends AbstractController
{
    #[Route('/revision', name: 'app_revision')]
    public function index(): Response
    {
        return $this->render('revision/index.html.twig', [
            'controller_name' => 'RevisionController',
        ]);
    }
}
