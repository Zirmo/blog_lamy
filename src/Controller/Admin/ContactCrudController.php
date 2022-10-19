<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use App\Entity\Contact;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ContactCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Contact::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnIndex()->setLabel('Id contact')->hideOnDetail(),
            TextField::new('Nom')->setLabel('nom'),
            TextField::new('Prenom')->setLabel('prenom'),
            EmailField::new('Email')->setLabel('email'),
            TextField::new('objet')->setLabel('objet'),
            TextEditorField::new('contenu')->setSortable( false)->hideOnIndex(),
            DateTimeField::new('createdAt')->hideOnForm()->setLabel('Créé le'),


        ];
    }
    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance,): void
    {
        // verifier que $entityInstance est une instance de la classe article
        if (!$entityInstance instanceof Contact) return;
        $entityInstance->setCreatedAt(new \DateTime());

        //appel a la methode heritee afin de persister l'entité
        parent::persistEntity($entityManager,$entityInstance);
    }

    public function configureCrud(Crud $crud): Crud
    {
        $crud->setPageTitle(Crud::PAGE_INDEX,'Liste des contacts');
        $crud->setDefaultSort(['createdAt' => 'DESC']);
        return $crud ;
    }
    public function configureActions(Actions $actions): Actions
    {

        $actions->add(Crud::PAGE_INDEX,Action::DETAIL);
        $actions->update(Crud::PAGE_INDEX,Action::DETAIL,function (Action $action){
            return $action->setLabel("Détail");});



        return $actions;


    }

    public function configureFilters(Filters $filters): Filters
    {
        $filters->add("createdAt");
        return $filters;
    }
}
