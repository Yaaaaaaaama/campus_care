<?php

namespace App\Controller;

use App\Entity\Incident;
use App\Form\IncidentFilterType;
use App\Form\AdminIncidentType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    #[Route('/admin/incidents', name: 'admin_incidents')]
    public function listIncidents(EntityManagerInterface $entityManager, Request $request): Response
    {
        // Créer le formulaire de filtre
        $form = $this->createForm(IncidentFilterType::class);
        $form->handleRequest($request);

        // Initialiser les critères pour la recherche
        $criteria = [];

        // Si le formulaire est soumis et valide, récupérer les données du filtre
        if ($form->isSubmitted() && $form->isValid()) {
            // Récupérer la localisation du filtre
            $location = $form->get('location')->getData();

            // Si une localisation est sélectionnée, l'ajouter aux critères
            if ($location) {
                $criteria['location'] = $location; // Ajouter le filtre sur la localisation
            }
        }

        // Récupérer les incidents en fonction des critères, triés par date décroissante (du plus récent au plus ancien)
        $incidents = $entityManager->getRepository(Incident::class)->findBy(
            $criteria,
            ['createdAt' => 'DESC'] // Tri par date de création
        );

        // Rendre la vue avec la liste des incidents et le formulaire de filtre
        return $this->render('Incident/adminIncidents.html.twig', [
            'incidents' => $incidents,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/admin/incidents/{id}/edit', name: 'admin_incident_edit')]
    public function editIncidentStatus(Incident $incident, Request $request, EntityManagerInterface $entityManager): Response
    {
        // Création du formulaire pour modifier le statut de l'incident
        $form = $this->createForm(AdminIncidentType::class, $incident);
        $form->handleRequest($request);

        // Si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            // Enregistrer les changements dans la base de données
            $entityManager->flush();

            // Ajouter un message flash pour indiquer le succès
            $this->addFlash('success', 'Statut de l\'incident mis à jour avec succès.');

            // Redirection vers la liste des incidents
            return $this->redirectToRoute('admin_incidents');
        }

        return $this->render('Incident/adminEditIncident.html.twig', [
            'incident' => $incident,
            'form' => $form->createView(),
        ]);
    }
}
