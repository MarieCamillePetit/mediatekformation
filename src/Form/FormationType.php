<?php

namespace App\Form;

use App\Entity\Formation;
use App\Entity\Niveau;
use DateTime;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FormationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('publishedAt', DateType::class, [
                'label' => 'Date de publication : ',
                'data' => new DateTime(),
                'required' => true,
            ])
            ->add('title', TextType::class, [
               'label' => 'Titre : ',
               'attr' => ['maxlength' => 90],
               'required' => true,
            ])
            ->add('description', TextareaType::class, [
               'label' => 'Description : ',
               'attr' => ['rows' => 6],
               'required' => false,
            ])
            ->add('miniature', UrlType::class, [
               'label' => 'Miniature URL : ',
               'attr' => ['maxlength' => 30],
               'required' => false,
            ])
            ->add('picture', UrlType::class, [
               'label' => 'Image URL : ',
               'attr' => ['maxlength' => 30],
               'required' => false,
            ])
            ->add('videoId', TextType::class, [
               'label' => 'Video ID : ',
               'attr' => ['maxlength' => 12],
               'required' => false, 
            ])
            ->add('niveau_id', EntityType::class, [
                'label' => 'Niveau : ',
                'class' => Niveau::class,
                'choice_label' => 'level',
                'required' => true,
            ])  
            ->add('submit', SubmitType::class, [
                'label' => 'Enregistrer',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Formation::class,
        ]);
    }
}