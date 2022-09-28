<?php

namespace App\DataFixtures;

use App\Entity\Commentaire;
use App\Entity\Utilisateur;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class UtilisateurFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker =Factory::create("fr_FR");

        for($i=1;$i<=14;$i ++){
            $utilisateur = new Utilisateur() ;
            $utilisateur->setPrenom($faker->firstname())
                ->setNom($faker->lastname())
                ->setPseudo($faker->word());
            $this->addReference("utilisateur".$i,$utilisateur);
            $manager->persist($utilisateur);
        }
        $manager->flush();
    }


}
