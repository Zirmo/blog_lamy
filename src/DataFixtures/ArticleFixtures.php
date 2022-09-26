<?php

namespace App\DataFixtures;

use App\Entity\Article;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class ArticleFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // initialiser faker

        $faker=Factory::create("fr_FR");
        for ($i=0;$i<50;$i++){
            $article =new Article() ;
            $article->setTitre($faker->words($faker->numberBetween(3,50),true))
                ->setContenu($faker->paragraphs(3,true))
            ->setCreatedAt($faker->dateTimeBetween('-6 months'));

            //generer l'ordre insert
            //INSERT INTO articule VALUES ("Titre 1","contenu 1")
            $manager->persist($article);
        }


        //envoyer l'ordre insert vers la base

        $manager->flush();
    }
}
