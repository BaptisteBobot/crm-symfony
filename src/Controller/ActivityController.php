<?php

namespace App\Controller;


use App\Entity\Activity;
use App\Entity\User;

use App\Form\ActivityType;
use App\Repository\ActivityRepository;
use App\Repository\ActivityUserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse; // Ajoutez cette ligne
use Symfony\Component\Routing\Annotation\Route;

#[Route('/home')]
class ActivityController extends AbstractController
{
    #[Route('/activities', name: 'activity_index')]
    public function index(ActivityRepository $activityRepository, ActivityUserRepository $activityUserRepository): Response
    {
        $registeredActivities = [];
    
        if ($this->getUser()) {
            $registeredActivities = $this->getRegisteredActivities($this->getUser(), $activityUserRepository);
        }
    
        return $this->render('activity/index.html.twig', [
            'activities' => $activityRepository->findAll(),
            'registeredActivities' => $registeredActivities
        ]);
    }
    private function getRegisteredActivities(User $user, ActivityUserRepository $activityUserRepository)
    {
    $activityUsers = $activityUserRepository->findBy(['user' => $user]);
    $activities = [];

    foreach ($activityUsers as $activityUser) {
        $activities[] = $activityUser->getActivity();
    }

    return $activities;
}

/**
 * @Route("/activities/create", name="activity_create", methods={"GET","POST"})
 */
public function create(Request $request, EntityManagerInterface $entityManager): Response
{
    if ($request->isXmlHttpRequest()) {
        $data = json_decode($request->getContent(), true);

        $activity = new Activity();
        $activity->setName($data['name']);
        $activity->setStartDate(new \DateTime($data['startDate']));
        $activity->setEndDate(new \DateTime($data['endDate']));
        $activity->setLocation($data['location']); // Ajoutez cette ligne

        $entityManager->persist($activity);
        $entityManager->flush();

        return new JsonResponse(['status' => 'success']);
    }

    $activity = new Activity();
    $form = $this->createForm(ActivityType::class, $activity);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager->persist($activity);
        $entityManager->flush();

        return $this->redirectToRoute('activity_index');
    }

    return $this->render('activity/create.html.twig', [
        'form' => $form->createView(),
    ]);
}

    /**
     * @Route("/activities/{id}/edit", name="activity_edit")
     */
    public function edit(Request $request, EntityManagerInterface $entityManager, Activity $activity): Response
    {
        $form = $this->createForm(ActivityType::class, $activity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('activity_index');
        }

        return $this->render('activity/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/activities/{id}/delete", name="activity_delete")
     */
    public function delete(EntityManagerInterface $entityManager, Activity $activity): Response
    {
        $entityManager->remove($activity);
        $entityManager->flush();

        return $this->redirectToRoute('activity_index');
    }
/**
 * @Route("/calendar", name="activity_calendar")
 */
public function calendar(ActivityRepository $activityRepository): Response
{
    $activities = $activityRepository->findAll();
    $calendarEvents = [];

    foreach ($activities as $activity) {
        $calendarEvents[] = [
            'title' => $activity->getName(),
            'start' => $activity->getStartDate()->format('Y-m-d\TH:i:s'),
            'end' => $activity->getEndDate()->format('Y-m-d\TH:i:s'),
        ];
    }

    return $this->render('activity/calendar.html.twig', [
        'calendarEvents' => $calendarEvents,
    ]);
}


}
