<?php

namespace App\Form;

use App\Entity\Incident;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;

class IncidentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('campus', ChoiceType::class, [ 
                'choices' => [
                    'Campus Paris' => 'paris',
                    'Campus Lille' => 'lille',
                    'Campus Montpellier' => 'montpellier',
                ],
                'label' => 'Sélectionnez le campus'
            ])
            ->add('category', ChoiceType::class, [ 
                'choices' => [
                    'Technique' => 'technique',
                    'Matériel' => 'matériel',
                    'Sanitaire' => 'sanitaire',
                    'Social' => 'social',
                    'Autres' => 'autres',
                ],
                'label' => 'Catégorie'
            ])
            ->add('location', ChoiceType::class, [ 
                'choices' => [ 
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
            ->add('description', TextareaType::class, [ 
                'label' => 'Description de l\'incident',
                'attr' => ['placeholder' => 'Décrivez le problème rencontré']
            ])
            ->add('photo', FileType::class, [
                'label' => 'Ajouter une photo (fichier JPG ou PNG ou prendre une photo)',
                'mapped' => false,
                'required' => false,
                'attr' => [
                    'accept' => 'image/jpeg,image/png',
                ],
                'constraints' => [
                    new File([
                        'maxSize' => '5M',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                        ],
                        'mimeTypesMessage' => 'Veuillez télécharger une image au format JPG ou PNG',
                    ])
                ],
            ]);
            
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Incident::class, 
        ]);
    }
}
