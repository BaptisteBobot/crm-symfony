<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BdeWebSiteController extends AbstractController
{
    #[Route('/bde/web/site', name: 'app_bde_web_site')]
    public function index(): Response
    {
        return $this->render('bde_web_site/index.html.twig', [
            'controller_name' => 'BdeWebSiteController',
        ]);
    }
}
