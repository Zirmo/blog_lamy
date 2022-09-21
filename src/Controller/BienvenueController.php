<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BienvenueController extends AbstractController
{
    #[Route('/welcome', name: 'app_bienvenue')]
    public function index(): Response
    {

        return $this->render('bienvenue/index.html.twig');
    }

    #[Route('/bienvenue/{nom}', name: 'app_bienvenue_personne')]
    public function bienvenuePersonne($nom): Response
    {

        return $this->render('bienvenue/bienvenue_personne.html.twig',[
            "nom"=>$nom
        ]);
    }
    #[Route('/bienvenus', name: 'app_bienvenus')]
    public function bienvenus(): Response
    {
        //declarer un tableau avec trois prenom
        $prenoms=["jack","iet","michel"];

        //la vue affiche la bienvenue aux 3 prénoms
        return $this->render('bienvenue/bienvenus.html.twig',["prenoms"=>$prenoms]);
    }
}

