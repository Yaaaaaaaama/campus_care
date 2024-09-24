<?php

namespace App\Form;

use App\Entity\Incident;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class IncidentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('category', ChoiceType::class, [ // Champ de sélection pour la catégorie
                'choices' => [
                    'Technique' => 'technique',
                    'Matériel' => 'matériel',
                    'Sanitaire' => 'sanitaire',
                    'Social' => 'social',
                    'Autres' => 'autres',
                ],
                'label' => 'Catégorie'
            ])
            ->add('description', TextareaType::class, [ // Champ de texte pour la description
                'label' => 'Description de l\'incident',
                'attr' => ['placeholder' => 'Décrivez le problème rencontré']
            ])
            ->add('location', ChoiceType::class, [ // Champ de sélection pour la localisation
                'choices' => [ // Liste des choix pour la localisation
                    'Salle 101' => 'salle101',
                    'Salle 102' => 'salle102',
                    'Salle 103' => 'salle103',
                    'Salle 104' => 'salle104',
                    'Salle 105' => 'salle105',
                    'Autres' => 'autres',
                ],
                'label' => 'Localisation',
                'placeholder' => 'Sélectionnez la localisation'
            ])
            ->add('campus', ChoiceType::class, [ // Champ de sélection pour le campus
                'choices' => [
                    'Campus Paris' => 'paris',
                    'Campus Lille' => 'lille',
                    'Campus Montpellier' => 'montpellier',
                ],
                'label' => 'Sélectionnez le campus'
            ])
            ->add('submit', SubmitType::class, [ // Bouton pour soumettre le formulaire
                'label' => 'Signaler l\'incident',
                'attr' => ['class' => 'btn btn-primary']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Incident::class, // L'entité liée au formulaire
        ]);
    }
}
