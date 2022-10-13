<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use App\Entity\Categorie;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\String\Slugger\SluggerInterface;

class CategorieCrudController extends AbstractCrudController
{
    private SluggerInterface $slugger ;


    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    public static function getEntityFqcn(): string
    {
        return Categorie::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('titre'),
            TextField::new('slug')->hideOnForm(),
        ];
    }
    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance,): void
    {
        // verifier que $entityInstance est une instance de la classe article
        if (!$entityInstance instanceof Categorie) return;
        $entityInstance->setSlug($this->slugger->slug($entityInstance->getTitre())->lower());

        //appel a la methode heritee afin de persister l'entité
        parent::persistEntity($entityManager,$entityInstance);
    }

}
