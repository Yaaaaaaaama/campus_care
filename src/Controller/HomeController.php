<?php

namespace App\Controller;

use App\Entity\Incident;
use App\Form\IncidentType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'homepage')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Création d'un nouvel incident
        $incident = new Incident();
        $form = $this->createForm(IncidentType::class, $incident);
        $form->handleRequest($request);

        // Si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            // Remplir le champ user avec l'utilisateur connecté
            $incident->setUser($this->getUser());
            
            // Persister et enregistrer l'incident
            $entityManager->persist($incident);
            $entityManager->flush();

            // Rediriger vers la page de succès
            return $this->redirectToRoute('incident_success');
        }

        // Affichage de la vue avec le formulaire
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'form' => $form->createView(),
        ]);
    }

    #[Route('/incident/success', name: 'incident_success')]
    public function success(): Response
    {
        return $this->render('incident/success.html.twig');
    }
}
