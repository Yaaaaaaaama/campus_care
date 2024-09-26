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
        $form = $this->createForm(IncidentFilterType::class);
        $form->handleRequest($request);

        $criteria = [];

        if ($form->isSubmitted() && $form->isValid()) {
            $location = $form->get('location')->getData();

            if ($location) {
                $criteria['location'] = $location; 
            }
        }

        $incidents = $entityManager->getRepository(Incident::class)->findBy(
            $criteria,
        );

        return $this->render('Incident/adminIncidents.html.twig', [
            'incidents' => $incidents,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/admin/incidents/{id}/edit', name: 'admin_incident_edit')]
    public function editIncidentStatus(Incident $incident, Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AdminIncidentType::class, $incident);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Statut de l\'incident mis à jour avec succès.');

            return $this->redirectToRoute('admin_incidents');
        }

        return $this->render('Incident/adminEditIncident.html.twig', [
            'incident' => $incident,
            'form' => $form->createView(),
        ]);
    }
}
