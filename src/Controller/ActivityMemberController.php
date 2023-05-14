<?php
// src/Controller/ActivityMemberController.php

namespace App\Controller;

use App\Entity\Activity;
use App\Entity\ActivityMember;
use App\Repository\ActivityMemberRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/home')]
class ActivityMemberController extends AbstractController
{
    #[Route('/registerActivity/{id}', name: 'activity_member_register')]
    public function register(Request $request, Activity $activity, EntityManagerInterface $entityManager): Response
    {
        // Récupérer l'utilisateur actuellement connecté (vous devez avoir un système d'authentification en place)
        $user = $this->getUser();

        $activityRegistration = new ActivityMember();
        $activityRegistration->setMember($user);
        $activityRegistration->setActivity($activity);

        $entityManager->persist($activityRegistration);
        $entityManager->flush();

        return $this->redirectToRoute('activity_show', ['id' => $activity->getId()]);
    }

    #[Route('/unregisterActivity/{id}', name: 'activity_member_unregister')]
    public function unregister(Request $request, Activity $activity, EntityManagerInterface $entityManager): Response
    {
        // Récupérer l'utilisateur actuellement connecté (vous devez avoir un système d'authentification en place)
        $user = $this->getUser();

        $activityRegistration = $entityManager->getRepository(ActivityMember::class)->findOneBy([
            'member' => $user,
            'activity' => $activity
        ]);

        if ($activityRegistration) {
            $entityManager->remove($activityRegistration);
            $entityManager->flush();
        }

        return $this->redirectToRoute('activity_show', ['id' => $activity->getId()]);
    }
}
