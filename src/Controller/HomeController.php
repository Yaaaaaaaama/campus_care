<?php

namespace App\Controller;

use App\Entity\Incident;
use App\Form\IncidentType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'homepage')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Rediriger les administrateurs vers le tableau de bord admin
        if ($this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('admin_incidents');
        }

        // Création d'un nouvel incident pour les utilisateurs classiques
        $incident = new Incident();
        $form = $this->createForm(IncidentType::class, $incident);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $incident->setUser($this->getUser());
            $entityManager->persist($incident);
            $entityManager->flush();

            $this->addFlash('success', 'Incident signalé avec succès !');
            return $this->redirectToRoute('incident_success');
        }

        return $this->render('Home/index.html.twig', [
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
