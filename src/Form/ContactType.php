<?php

namespace App\Form;

use App\Entity\Contact;
use App\Entity\Hotelier;
use App\Repository\HotelierRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('subject', TextType::class, [
                'label' => 'Sujet'
            ])
            ->add('message', TextareaType::class, [
                'label' => 'Message'
            ])
            ->add('receiver', EntityType::class,
                [
                    'label' => 'A qui voulez-vous envoyer ce message ?',
                    'class' => 'App:User',
                    'choice_label' => 'hotelsOwn',
                    'multiple' => false,
                    'expanded' => false
                ])
            ->add('receiver', EntityType::class, [
                'label' => 'A qui voulez-vous envoyer ce message ?',
                'class' => 'App\Entity\Hotelier',
                'choice_label' => function (Hotelier $hotelier) {
                    return $hotelier->getHotelsOwn()->getOwner()->getEmail();
                }
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // uncomment if you want to bind to a class
            'data_class' => Contact::class
        ]);
    }
}
