<?php
// src/Controller/ActivityUserController.php

namespace App\Controller;

use App\Entity\Activity;
use App\Entity\ActivityUser;
use App\Entity\Member;
use App\Repository\ActivityRepository;
use App\Repository\ActivityUserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/home')]
class ActivityUserController extends AbstractController
{
    #[Route('/registerActivity/{id}', name: 'activity_user_register')]
    public function register(Request $request, Activity $activity, EntityManagerInterface $entityManager): Response
    {
        // Récupérer le user actuellement connecté (vous devez avoir un système d'authentification en place)
        $user = $this->getUser();
        //get member by user

        $activityRegistration = new ActivityUser();
        $activityRegistration->setUser($user);
        $activityRegistration->setActivity($activity);

        $entityManager->persist($activityRegistration);
        $entityManager->flush();

        return $this->redirectToRoute('activity_show', ['id' => $activity->getId()]);
    }

    #[Route('/unregisterActivity/{id}', name: 'activity_user_unregister')]
    public function unregister(Request $request, Activity $activity, EntityManagerInterface $entityManager): Response
    {
        // Récupérer l'utilisateur actuellement connecté (vous devez avoir un système d'authentification en place)
        $user = $this->getUser();

        $activityRegistration = $entityManager->getRepository(ActivityUser::class)->findOneBy([
            'user' => $user,
            'activity' => $activity
        ]);

        if ($activityRegistration) {
            $entityManager->remove($activityRegistration);
            $entityManager->flush();
        }

        return $this->redirectToRoute('activity_show', ['id' => $activity->getId()]);
    }

    #[Route('/activities/registered/{id}', name: 'activity_show')]
    public function show(Activity $activity, ActivityUserRepository $activityUserRepository, ActivityRepository $activityRepository): Response
    {
        // Récupérer l'utilisateur actuellement connecté (vous devez avoir un système d'authentification en place)
        $user = $this->getUser();

        $userRegistered = $activityUserRepository->findBy([
            'user' => $user
        ]);
//        dump($userRegistered);
//        exit;


        return $this->render('activity/show.html.twig', [
            'activities' => $userRegistered
        ]);
    }

}
