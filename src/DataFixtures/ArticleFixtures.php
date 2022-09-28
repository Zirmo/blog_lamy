<?php

namespace App\DataFixtures;

use App\Entity\Article;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\String\Slugger\SluggerInterface;

class ArticleFixtures extends Fixture implements DependentFixtureInterface
{
    private SluggerInterface $slugger ;

    //demander a symfony d'injecter le slugger au niveau du constructeur


    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }


    public function load(ObjectManager $manager): void
    {
        // initialiser faker

        $faker=Factory::create("fr_FR");
        for ($i=0;$i<100;$i++){
            $article =new Article() ;
            $article->setTitre($faker->words($faker->numberBetween(3,5),true))
                ->setContenu($faker->paragraphs(3,true))
            ->setCreatedAt($faker->dateTimeBetween('-6 months'))
            ->setSlug($this->slugger->slug($article->getTitre())->lower());
            //associer l'article a une categorie
            //recuperer une reference d'une categorie
            $numCategorie = $faker->numberBetween(0,8);
            $article->setCategorie($this->getReference("categorie".$numCategorie));

            $this->addReference("article".$i,$article);
            //generer l'ordre insert
            //INSERT INTO articule VALUES ("Titre 1","contenu 1")
            $manager->persist($article);
        }


        //envoyer l'ordre insert vers la base

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            CategorieFixtures::class
        ];
    }
}
