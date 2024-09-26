<?php

namespace App\Controller;

use App\Entity\Incident;
use App\Form\IncidentType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface; 

class IncidentController extends AbstractController
{
    /**
     * @Route("/incident/new", name="incident_new")
     */
    public function new(Request $request, SluggerInterface $slugger, EntityManagerInterface $entityManager, LoggerInterface $logger) // Correction ici
    {
        $incident = new Incident();
        $form = $this->createForm(IncidentType::class, $incident);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $logger->info('Formulaire soumis avec succès.');

            $incident->setUser($this->getUser());

            $photo = $form->get('photo')->getData();

            if ($photo) {
                $originalFilename = pathinfo($photo->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$photo->guessExtension();

                try {
                    $photo->move(
                        $this->getParameter('photos_directory'), 
                        $newFilename
                    );
                    $incident->setPhoto($newFilename);
                    $logger->info('Photo téléchargée avec succès : ' . $newFilename);
                } catch (FileException $e) {
                    $logger->error('Erreur lors du téléchargement de la photo : ' . $e->getMessage());
                    $this->addFlash('error', 'Erreur lors du téléchargement de la photo : ' . $e->getMessage());
                }
            } else {
                $logger->warning('Aucune photo n\'a été téléchargée.');
                $this->addFlash('error', 'Aucune photo n\'a été téléchargée.');
            }

            $entityManager->persist($incident);
            try {
                $entityManager->flush();
                $logger->info('Incident enregistré avec succès : ' . json_encode($incident));
            } catch (\Exception $e) {
                $logger->error('Erreur lors de l\'enregistrement de l\'incident : ' . $e->getMessage());
            }

            return $this->redirectToRoute('incident_success'); 
        }

        return $this->render('home/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/incidents", name="user_incidents")
     */
    public function userIncidents(EntityManagerInterface $entityManager)
    {
        $user = $this->getUser();

        $incidents = $entityManager->getRepository(Incident::class)->findBy(['user' => $user]);

        return $this->render('incident/userIncident.html.twig', [
            'incidents' => $incidents,
        ]);
    }
}
