<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use App\Repository\CategorieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategorieController extends AbstractController
{
    private CategorieRepository $categorieRepository ;
    private ArticleRepository $articleRepository ;

    /**
     * @param CategorieRepository $categorieRepository
     */
    public function __construct(CategorieRepository $categorieRepository,ArticleRepository $articleRepository)
    {
        $this->categorieRepository = $categorieRepository;
        $this->articleRepository = $articleRepository;
    }


    #[Route('/categories', name: 'app_categories')]
    public function index(): Response
    {
        $categories = $this->categorieRepository->findBy([],["titre"=>"ASC"]);
        return $this->render('categorie/index.html.twig', [
            'categories' => $categories,
        ]);
    }

    #[Route('/categorie/{slug}', name: 'app_categorie_slug')]
    public function getArticleWithCategorie($slug): Response
    {
        // recuperer les infos dans la base de donnees
        // le controleur fais appel au modele (classe du modele) afin de recuperer la liste des articles
        // $repository = new ArticleRepository();
        $articles = $this->articleRepository->findBy(["categorie"=>$this->categorieRepository->findOneBy(["slug"=>$slug])]);
        $categorie =$this->categorieRepository->findOneBy(["slug"=> $slug]);

        return $this->render('categorie/articles_categorie.html.twig',[
            "articles" => $articles,
            "categorie" => $categorie
        ]);
    }


}
