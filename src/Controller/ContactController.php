<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Contact;
use App\Form\ArticleType;
use App\Form\ContactType;
use App\Repository\ContactRepository;
use App\Service\EmailService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class ContactController extends AbstractController
{
    private ContactRepository $contactRepository;

    /**
     * @param ContactRepository $contactRepository
     */
    public function __construct(ContactRepository $contactRepository)
    {
        $this->contactRepository = $contactRepository;
    }


    #[Route('/contact', name: 'app_contact')]
    public function index( Request $request, EmailService $emailService): Response
    {
        $contact = new Contact();

        //creation du formulaire
        $formContact = $this->createForm(ContactType::class, $contact);

        // reconnaitre si le formulaire a ete soumis ou pas
        $formContact->handleRequest($request);
        // est ce que le formulaire a ete soumis
        if ($formContact->isSubmitted() && $formContact->isValid()) {

            $emailService->envoyerEmail($contact->getEmail(),"destinateur@test.fr",$contact->getObjet(),"email/email.html.twig",[
                "prenom"=>$contact->getPrenom(),
                "nom"=>$contact->getNom(),
                "objet"=>$contact->getObjet(),
                "contenu"=>$contact->getContenu()
            ]);
            $contact->setCreatedAt(new \DateTime());
            //inserer l'article dans la base de données
            $this->contactRepository->add($contact, true);
            return $this->redirectToRoute('app_article');
        }

        //appel de la vue twig permettant d'afficher le formulaire


        return $this->renderForm('contact/contact.html.twig', [
            "formContact" => $formContact
        ]);


    }
}
