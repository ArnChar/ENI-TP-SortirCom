<?php

namespace App\Form;

use App\Entity\Campus;
use App\Form\Model\HomeCriteriaModel;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class HomeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('campus', EntityType::class, [
                'label' => 'home.label.filter_campus',
                'class' => Campus::class,
                'choice_label' => 'nom'
            ])
            ->add('name', TextType::class, [
                'label' => 'home.label.filter_name',
                'required' => false
            ])
            ->add('dateFrom', DateTimeType::class, [
                'label' => 'home.label.filter_from_date',
                'widget' => 'single_text',
                'required' => false
            ])
            ->add('dateTo', DateTimeType::class, [
                'label' => 'home.label.filter_to_date',
                'widget' => 'single_text',
                'required' => false
            ])
            ->add('manager', CheckboxType::class, [
                'label' => 'home.label.filter_manager',
                'required' => false
            ])
            ->add('subscribed', CheckboxType::class, [
                'label' => 'home.label.filter_subscribed',
                'required' => false
            ])
            ->add('notSubscribed', CheckboxType::class, [
                'label' => 'home.label.filter_not_subscribed',
                'required' => false
            ])
            ->add('outdated', CheckboxType::class, [
                'label' => 'home.label.filter_outdated',
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => HomeCriteriaModel::class
        ]);
    }
}
