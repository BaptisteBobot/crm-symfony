<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Github\Client;
use App\Form\DeployType;
class ProjectController extends AbstractController
{
      
        #[Route('/upload', name: 'upload')]
        public function upload(Request $request)
        {
            $form = $this->createForm(DeployType::class);
            $form->handleRequest($request);
    
            if ($form->isSubmitted() && $form->isValid()) {
                $file = $form->get('projectFile')->getData();
    
                // Vérifiez ici si le fichier est un fichier ZIP valide
                // Utilisez les méthodes appropriées pour effectuer des vérifications sur le fichier
                
                // Générez un nom de fichier unique
                $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $extension = $file->getClientOriginalExtension();
                $filename = $originalFilename . '_' . uniqid() . '.' . $extension;
    
                try {
                    $file->move(
                        $this->getParameter('upload_directory'),
                        $filename
                    );
                } catch (FileException $e) {
                    // Gérez l'exception si une erreur se produit lors du déplacement du fichier
                }
    
                // Après le téléchargement, envoyez-le à GitHub
    
                $client = new Client();
                $client->authenticate('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', null, Client::AUTH_ACCESS_TOKEN);
    
                $filePath = $this->getParameter('upload_directory') . '/' . $filename;
    
                $repositoryOwner = 'AhmedBouk';
                $repositoryName = 'DeployApp';
                $branch = 'master';
    
                $repositoryContents = $client->api('repo')->contents();
    
                $commitMessage = 'Deployment file uploaded';
                $commitPath = 'deployments/' . $filename;
    
                $repositoryContents->create($repositoryOwner, $repositoryName, $commitPath, file_get_contents($filePath), $commitMessage, $branch);
    
                return $this->redirectToRoute('upload_success'); // Supposons que vous ayez cette route configurée
            }
    
            return $this->render('project/upload.html.twig', [
                'form' => $form->createView(),
            ]);
        }
}
