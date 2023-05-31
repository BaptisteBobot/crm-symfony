<?php

namespace App\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Controller\Admin\ArticleCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        // return parent::index();

        // Option 1. You can make your dashboard redirect to some common page of your backend
        //
         $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        return $this->redirect($adminUrlGenerator->setController(ArticleCrudController::class)->generateUrl());
        // $url = $this->$adminUrlGenerator->setController(UserCrudController::class)->generateUrl();
        // Option 2. You can make your dashboard redirect to different pages depending on the user
        //
        // if ('jane' === $this->getUser()->getUsername()) {
        //     return $this->redirect('...');
        // }

        // Option 3. You can render some custom template to display a proper dashboard with widgets, etc.
        // (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)
        //
        // return $this->render('some/path/my-dashboard.html.twig');
        // return $this->redirect($url);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Crm Symfony');
    }

    public function configureMenuItems(): iterable
    {
        yield  MenuItem::section('Users');
        yield MenuItem::linkToCrud('Users', 'fa fa-user', UserCrudController::getEntityFqcn());
        yield MenuItem::linkToCrud('Activities', 'fa fa-calendar', ActivityCrudController::getEntityFqcn());
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');

        yield  MenuItem::section('Révision');
        yield MenuItem::linkToCrud('Listes', 'fa fa-user', RevisionCrudController::getEntityFqcn());

    }
}
