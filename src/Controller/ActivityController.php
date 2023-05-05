<?php

namespace App\Controller;

use App\Entity\Activity;
use App\Form\ActivityType;
use App\Repository\ActivityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ActivityController extends AbstractController
{
    /**
     * @Route("/activities", name="activity_index")
     */
    public function index(ActivityRepository $activityRepository): Response
    {
        $activities = $activityRepository->findAll();

        return $this->render('activity/index.html.twig', [
            'activities' => $activities,
        ]);
    }

    /**
     * @Route("/activities/create", name="activity_create")
     */
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
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
