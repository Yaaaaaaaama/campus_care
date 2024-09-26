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
use Psr\Log\LoggerInterface; // Ajoutez cette ligne

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
            // Log le début de la soumission du formulaire
            $logger->info('Formulaire soumis avec succès.');

            // Lier l'incident à l'utilisateur
            $incident->setUser($this->getUser());

            // Récupération du fichier photo
            $photo = $form->get('photo')->getData();

            if ($photo) {
                // Générer un nom unique pour le fichier
                $originalFilename = pathinfo($photo->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$photo->guessExtension();

                // Déplacer le fichier dans le répertoire de destination
                try {
                    $photo->move(
                        $this->getParameter('photos_directory'), // Assurez-vous que ce paramètre est configuré dans les paramètres
                        $newFilename
                    );
                    // Stocker le chemin de la photo dans l'entité Incident
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

            // Enregistrer l'entité Incident en base de données
            $entityManager->persist($incident);
            try {
                $entityManager->flush();
                $logger->info('Incident enregistré avec succès : ' . json_encode($incident));
            } catch (\Exception $e) {
                $logger->error('Erreur lors de l\'enregistrement de l\'incident : ' . $e->getMessage());
            }

            // Redirection ou autre action après soumission réussie
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
        // Récupérer l'utilisateur connecté
        $user = $this->getUser();

        // Récupérer les incidents de l'utilisateur
        $incidents = $entityManager->getRepository(Incident::class)->findBy(['user' => $user]);

        return $this->render('incident/userIncident.html.twig', [
            'incidents' => $incidents,
        ]);
    }
}
