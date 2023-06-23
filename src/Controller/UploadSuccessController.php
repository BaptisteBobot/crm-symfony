<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UploadSuccessController extends AbstractController
{
    #[Route('/upload/success', name: 'app_upload_success')]
    public function index(): Response
    {
        return $this->render('upload_success/index.html.twig');

    }
}
