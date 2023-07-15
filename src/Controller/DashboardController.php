<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Repository\UserRepository;
use App\Repository\ActivityRepository;

class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'app_dashboard')]
    public function index(UserRepository $userRepository, ActivityRepository $activityRepository)
    {
        // Get the total count of users
        $totalUsers = $userRepository->count([]);
    
        // Get the latest 10 activities
        $latestActivities = $activityRepository->findBy([], ['startDate' => 'DESC'], 5);
    
        // Get the count of users created each month in the current year
        $monthlyUserCounts = $userRepository->findMonthlyUserCountForCurrentYear();
    
        // Get the count of activities each month in the current year
        $monthlyActivityCounts = $activityRepository->findMonthlyActivityCountForCurrentYear();
    
        return $this->render('dashboard/index.html.twig', [
            'totalUsers' => $totalUsers,
            'latestActivities' => $latestActivities,
            'monthlyUserCounts' => $monthlyUserCounts,
            'monthlyActivityCounts' => $monthlyActivityCounts, // Assurez-vous que cela est bien défini
        ]);
    }
    
}