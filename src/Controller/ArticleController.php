<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    private ArticleRepository $articleRepository ;

    /**
     * @param ArticleRepository $articleRepository
     *
     */
    public function __construct(ArticleRepository $articleRepository)
    {
        $this->articleRepository = $articleRepository;
    }

    #[Route('/articles', name: 'app_article')]

    // à l'appel de la methode, symfony vas creer un objet de la classe ArticleRepository et le passer en parametre de la methode
    // mecanisme : INJECTION DE DEPENDANCES
    public function getArticles(): Response
    {
        // recuperer les infos dans la base de donnees
        // le controleur fais appel au modele (classe du modele) afin de recuperer la liste des articles
        // $repository = new ArticleRepository();
        $articles = $this->articleRepository->findBy([],['createdAt' => 'DESC'],5);

        return $this->render('article/index.html.twig',[
            "articles" => $articles
        ]);
    }

    #[Route('/article/{id}', name: 'app_article_id')]
    public function getArticle($id): Response
    {
        // recuperer les infos dans la base de donnees
        // le controleur fais appel au modele (classe du modele) afin de recuperer la liste des articles
        // $repository = new ArticleRepository();
        $article = $this->articleRepository->find($id);

        return $this->render('article/contenue_article.html.twig',[
            "article" => $article
        ]);
    }

}
