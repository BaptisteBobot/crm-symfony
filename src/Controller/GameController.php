<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/home/games')]
class GameController extends AbstractController
{
    #[Route('/', name: 'games', methods: ['GET'])]
    public function games(): Response
    {
        return $this->render('game/index.html.twig');
    }
}
