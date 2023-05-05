<?php
// src/Controller/ActivityMemberController.php
namespace App\Controller;

use App\Repository\ActivityMemberRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

// class ActivityMemberController extends AbstractController
// {
//     #[Route('/activity-member', name: 'activity_member')]
//     public function showActivityMembers(ActivityMemberRepository $activityMemberRepository): Response
//     {
//         $activityMembers = $activityMemberRepository->findAll();

//         return $this->render('activity_member/index.html.twig', [
//             'activity_members' => $activityMembers,
//         ]);
//     }
// }
// src/Controller/ActivityMemberController.php
class ActivityMemberController extends AbstractController
{
    #[Route('/activity-member', name: 'activity_member')]
    public function showActivityMembers(ActivityMemberRepository $activityMemberRepository, int $memberId): Response
    {
        $activityMembers = $activityMemberRepository->findByMemberId($memberId);

        $activities = [];
        foreach ($activityMembers as $activityMember) {
            $activities[] = $activityMember->getActivity();
        }

        return $this->render('members/activities.html.twig', [
            'activities' => $activities,
        ]);
    }
}