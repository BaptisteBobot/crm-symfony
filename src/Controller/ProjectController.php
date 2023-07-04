<?php
// ProjectController.php
// ProjectController.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Github\Client;
use App\Form\DeployType;
use DateTime;

class ProjectController extends AbstractController
{
    #[Route('/upload', name: 'upload')]
    public function upload(Request $request)
    {
        $form = $this->createForm(DeployType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('projectFile')->getData();
            $projectType = $form->get('projectType')->getData();

            // Générez un nom de fichier unique
            $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
            $filename = $originalFilename . '_' . (new DateTime())->format('Y-m-d_H-i-s') . '_' . $projectType . '.' . $extension;

            // Générez un nom de dossier unique basé sur l'heure du déploiement et le nom du fichier ZIP
            $deployDateTime = new DateTime();
            $deployDir = $originalFilename . '_' . $deployDateTime->format('Y-m-d_H-i-s'). '_'. $projectType  ;

            // Créez le dossier de déploiement
            $deployDirectory = $this->getParameter('upload_directory') . '/' . $deployDir;
            if (!is_dir($deployDirectory)) {
                mkdir($deployDirectory, 0777, true);
            }

            try {
                $file->move($deployDirectory, $filename);
            } catch (FileException $e) {
                // Gérez l'exception si une erreur se produit lors du déplacement du fichier
            }

            // Après le téléchargement, envoyez-le à GitHub

            $client = new Client();
            $client->authenticate('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', null, Client::AUTH_ACCESS_TOKEN);

            $filePath = $deployDirectory . '/' . $filename;

            $repositoryOwner = 'AhmedBouk';
            $repositoryName = 'DeployApp';
            $branch = 'master';

            $repositoryContents = $client->api('repo')->contents();

            $commitMessage = 'Deployment file uploaded'. '_' . $deployDir . '/' . $filename;
            $commitPath = 'deployments/' . $deployDir . '/' . $filename;

            $repositoryContents->create($repositoryOwner, $repositoryName, $commitPath, file_get_contents($filePath), $commitMessage, $branch);

            return $this->redirectToRoute('upload_success'); // Supposons que vous ayez cette route configurée
        }

        return $this->render('project/upload.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
