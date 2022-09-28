<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\Commentaire;
use App\Entity\Utilisateur;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class CommentaireFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker=Factory::create("fr_FR");
        for ($i=0;$i<120;$i++){
            $commentaire =new Commentaire() ;
            $commentaire->setContenu($faker->paragraphs(3,true))
                ->setCreatedAt($faker->dateTimeBetween('-6 months'));

            //associer l'article a une categorie
            //recuperer une reference d'une categorie
            $numUtilisateur = $faker->numberBetween(1,14);

            if( $faker->numberBetween(1,3) != 2){
                $commentaire->setUtilisateurId($this->getReference("utilisateur".$numUtilisateur ));
            }

            $numArticle = $faker->numberBetween(0,99);
            $commentaire->setArticleId($this->getReference("article". $numArticle ));
            //generer l'ordre insert
            //INSERT INTO articule VALUES ("Titre 1","contenu 1")
            $manager->persist($commentaire);
        }


        //envoyer l'ordre insert vers la base

        $manager->flush();
    }
    public function getDependencies()
    {
        return [
            UtilisateurFixtures::class,ArticleFixtures::class
        ];
    }

}
