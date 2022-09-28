<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\String\Slugger\SluggerInterface;
use App\Entity\Categorie;

class CategorieFixtures extends Fixture
{
    private SluggerInterface $slugger ;


    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }


    public function load(ObjectManager $manager): void
    {
        $faker =Factory::create("fr_FR");

        for($i=0;$i<=8;$i ++){
            $categorie = new Categorie() ;
            $categorie->setTitre($faker->word())
            ->setSlug($this->slugger->slug($categorie->getTitre())->lower());
            //Creer une reference sur la categorie
            $this->addReference("categorie".$i,$categorie);
            $manager->persist($categorie);
        }
        $manager->flush();
    }
}
