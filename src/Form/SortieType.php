<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\Lieu;
use App\Entity\Sortie;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SortieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom',TextType::class, [
                'label' => 'sortie.label.name',
                'required' => true
            ])
            ->add('dateHeureDebut',DateTimeType::class, [
                'label' => 'sortie.label.date',
                'widget' => 'single_text',
                'required' => true
            ])
            ->add('dateLimiteInscription',DateTimeType::class, [
                'label' => 'sortie.label.closure',
                'widget' => 'single_text',
                'required' => true
            ])
            ->add('nbInscriptionsMax', IntegerType::class, [
                'label' => 'sortie.label.capacity',
                'required' => true
            ])
            ->add('duree', IntegerType::class, [
                'label' => 'sortie.label.duration',
                'required' => true
            ])
            ->add('infosSortie', TextareaType::class, [
                'label' => 'sortie.label.description',
                'required' => true
            ])
            ->add('campus', EntityType::class, [
                'label' => 'sortie.label.campus',
                'class' => Campus::class,
                'choice_label' => 'nom'
            ])
            ->add('lieu', EntityType::class, [
                'label' => 'sortie.label.location',
                'class' => Lieu::class,
                'choice_label' => 'nom'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }
}
