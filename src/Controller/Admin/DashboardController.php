<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use App\Entity\Categorie;
use App\Entity\Contact;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);

        //generer un url afin 'acceder a la page d'accueil du crud des articles

        $url = $adminUrlGenerator->setController(ArticleCrudController::class)
                                    ->generateUrl();
        //rediriger vers cette url

        return $this->redirect($url);

    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('le One Piece est dans mon jardin');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToUrl("Accueil","fa fa-route",$this->generateUrl('accueil_blog'));
        yield MenuItem::linkToDashboard('Dashboard', " fas fa-mosquito");
        yield MenuItem::section("Article");
        //creer un sous menu pour article
        yield MenuItem::subMenu("Actions","fa fa-bars")
        ->setSubItems([

            MenuItem::linkToCrud("Lister Les articles","fas fa-eye",Article::class)->setAction(Crud::PAGE_INDEX)
                ->setDefaultSort(['createdAt' => 'DESC']),
            MenuItem::linkToCrud("ajouter Article","fas fa-plus",Article::class)->setAction(Crud::PAGE_NEW),
        ]);
        yield MenuItem::section("Categorie");
        //creer un sous menu pour categorie
        yield MenuItem::subMenu("Actions","fa fa-bars")
            ->setSubItems([

                MenuItem::linkToCrud("Lister Categories","fas fa-eye",Categorie::class)->setAction(Crud::PAGE_INDEX),
                MenuItem::linkToCrud("ajouter Categorie","fas fa-plus",Categorie::class)->setAction(Crud::PAGE_NEW),
            ]);

        yield MenuItem::section("Contact");
        //creer un sous menu pour categorie
        yield MenuItem::subMenu("Actions","fa fa-bars")
            ->setSubItems([
               MenuItem::linkToCrud("Lister Message","fas fa-letter",Contact::class)->setAction(Crud::PAGE_INDEX)
            ]);



    }
}
