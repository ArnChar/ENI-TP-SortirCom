<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\Participant;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ParticipantType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'participant.label.email',
                'required' => true,
            ])
            ->add('pseudo', TextType::class, [
                'label' => 'participant.label.userid',
                'required' => true
            ])
            ->add('nom', TextType::class, [
                'label' => 'participant.label.lastname'
            ])
            ->add('prenom', TextType::class, [
                'label' => 'participant.label.firstname'
            ])
            ->add('telephone', TextType::class, [
                'label' => 'participant.label.phone'
            ])
            ->add('campus', EntityType::class, [
                'label' => 'participant.label.campus',
                'class' => Campus::class,
                'choice_label' => 'nom'
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'participant.danger.password_check',
                'required' => true,
                'first_options'  => ['label' => 'participant.label.password'],
                'second_options' => ['label' => 'participant.label.password_check'],
            ])        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Participant::class,
        ]);
    }
}
