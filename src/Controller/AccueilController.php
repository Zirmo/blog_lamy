<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccueilController extends AbstractController
{
    private ArticleRepository $articleRepository;

    /**
     * @param ArticleRepository $articleRepository
     */
    public function __construct(ArticleRepository $articleRepository)
    {
        $this->articleRepository = $articleRepository;
    }

    #[Route('/', name: 'accueil_blog')]

    // à l'appel de la methode, symfony vas creer un objet de la classe ArticleRepository et le passer en parametre de la methode
        // mecanisme : INJECTION DE DEPENDANCES
    public function getArticles(): Response
    {
        // recuperer les infos dans la base de donnees
        // le controleur fais appel au modele (classe du modele) afin de recuperer la liste des articles
        // $repository = new ArticleRepository();
        $articles = $this->articleRepository->findBy(["isPublie" => true],['createdAt' => 'DESC'],10);

        return $this->render('accueil/index.html.twig',[
            "articles" => $articles
        ]);
    }

}
