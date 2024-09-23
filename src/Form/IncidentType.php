<?php

namespace App\Form;

use App\Entity\Incident;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class IncidentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('category', ChoiceType::class, [ // Champ choix pour la catégorie
            'choices' => [
                'Technique' => 'technique',
                'Matériel' => 'matériel',
                'Sanitaire' => 'sanitaire',
                'Social' => 'social',
                'Autres' => 'autres',
            ],
        ])
            ->add('description', TextareaType::class) // Champ texte pour la description
            ->add('location', TextType::class) // Champ texte pour la localisation
            ->add('submit', SubmitType::class, ['label' => 'Signaler l\'incident']);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Incident::class,
        ]);
    }
}
