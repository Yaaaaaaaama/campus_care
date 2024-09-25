<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class IncidentFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('location', ChoiceType::class, [
                'choices' => [
                    'Salle 101' => 'salle101',
                    'Salle 102' => 'salle102',
                    'Salle 103' => 'salle103',
                    'Salle 104' => 'salle104',
                    'Salle 105' => 'salle105',
                    'Autres' => 'autres',
                ],
                'label' => 'Filtrer par localisation',
                'placeholder' => 'Sélectionnez une salle',
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Pas de classe de données ici, car c'est un filtre
        ]);
    }
}
