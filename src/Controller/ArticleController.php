<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use App\Repository\CategorieRepository;
use App\Repository\CommentaireRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class ArticleController extends AbstractController
{
    private ArticleRepository $articleRepository ;
    private CommentaireRepository $commentaireRepository;




    public function __construct(ArticleRepository $articleRepository, CommentaireRepository $commentaireRepository)
    {
        $this->articleRepository = $articleRepository;
        $this->commentaireRepository=$commentaireRepository ;


    }

    #[Route('/articles', name: 'app_article')]

    // à l'appel de la methode, symfony vas creer un objet de la classe ArticleRepository et le passer en parametre de la methode
    // mecanisme : INJECTION DE DEPENDANCES
    public function getArticles(PaginatorInterface $paginator, Request $request): Response
    {
        // recuperer les infos dans la base de donnees
        // le controleur fais appel au modele (classe du modele) afin de recuperer la liste des articles
        // $repository = new ArticleRepository();

        //mise en place de la pagination

        $articles = $paginator->paginate(
            $this->articleRepository->findBy([],['createdAt' => 'DESC'],),
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );

        return $this->render('article/index.html.twig',[
            "articles" => $articles
        ]);
    }

    #[Route('/articles/{slug}', name: 'app_article_slug')]
    public function getArticle($slug): Response
    {
        // recuperer les infos dans la base de donnees
        // le controleur fais appel au modele (classe du modele) afin de recuperer la liste des articles
        // $repository = new ArticleRepository();
        $article = $this->articleRepository->findOneBy(["slug"=>$slug]);
       $commentaires=$this->commentaireRepository->findBy(["article_id"=>$this->articleRepository->findBy(["slug"=>$slug])]);
        return $this->render('article/contenue_article.html.twig',[
            "article" => $article,
            "commentaires"=>$commentaires
        ]);
    }

    #[Route('/articles/nouveau', name: 'app_article_nouveau',methods: ['GET', 'POST'],priority: 1 )]
    public function insert(SluggerInterface $slugger , Request $request ) :Response {
        $article = new Article() ;

        //creation du formulaire
        $formArticle=$this->createForm(ArticleType::class , $article);

        // reconnaitre si le formulaire a ete soumis ou pas
        $formArticle -> handleRequest($request);
        // est ce que le formulaire a ete soumis
        if ($formArticle->isSubmitted() && $formArticle->isValid()){
            $article->setSlug($slugger->slug($article->getTitre())->lower())
            ->setCreatedAt(new \DateTime());
            //inserer l'article dans la base de données
            $this->articleRepository->add($article,true);
            return $this->redirectToRoute('app_article');
        }

        //appel de la vue twig permettant d'afficher le formulaire


        return $this->renderForm('article/nouveau.html.twig',[
            "formArticle"=>$formArticle
        ]);



        /*$article->setTitre('Nouvel Article 2')->setContenu("Contenu de l'Article 2")->setSlug($slugger->slug($article->getTitre())->lower())->setCreatedAt(new \DateTime());
        //symfony 6 seulement
        $this->articleRepository->add($article,true);

        return $this->redirectToRoute("app_article"); */

    }
}
