<?php

namespace App\Controller;

use App\Entity\Incident;
use App\Form\IncidentType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IncidentController extends AbstractController
{
    #[Route('/incident/new', name: 'incident_new')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $incident = new Incident();
        $form = $this->createForm(IncidentType::class, $incident);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Remplir le champ user si nécessaire
            // $incident->setUser($this->getUser());

            $entityManager->persist($incident);
            $entityManager->flush();

            return $this->redirectToRoute('incident_success'); // Rediriger après succès
        }

        return $this->render('Incident/new_incident.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
