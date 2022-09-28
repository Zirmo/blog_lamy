<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use App\Repository\CategorieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    private ArticleRepository $articleRepository ;



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
        $articles = $this->articleRepository->findBy([],['createdAt' => 'DESC'],);

        return $this->render('article/index.html.twig',[
            "articles" => $articles
        ]);
    }

    #[Route('/article/{slug}', name: 'app_article_slug')]
    public function getArticle($slug): Response
    {
        // recuperer les infos dans la base de donnees
        // le controleur fais appel au modele (classe du modele) afin de recuperer la liste des articles
        // $repository = new ArticleRepository();
        $article = $this->articleRepository->findOneBy(["slug"=>$slug]);


        return $this->render('article/contenue_article.html.twig',[
            "article" => $article,

        ]);
    }

}
