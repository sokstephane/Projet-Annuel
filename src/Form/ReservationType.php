<?php

namespace App\Form;

use App\Entity\Reservation;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class ReservationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $bedRoom = $options['bedRoom'];
        $builder
            ->add('startDate', DateType::class, [
                'label' => 'register.startDate',
                'widget' => 'single_text'
            ])
            ->add('finalDate', DateType::class, [
                'label' => 'register.finalDate',
                'widget' => 'single_text'
            ])
            ->add('nbOfPersons', IntegerType::class, [
                'label' => 'register.nbOfPersons',
                'constraints' => array(
                    new NotBlank(["message" => "Le nombre de personnes est obligatoire"]),
                ),
            ])
            ->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) use ($bedRoom) {
                $form = $event->getForm();
                $currentDate = new \DateTime('now');
                $currentDate->setTime(0,0,0);
                $data = $event->getData();
                if ($data->getStartDate() >= $data->getFinalDate()) {
                    $form->get('finalDate')->addError(new FormError("La date d'arrivée doit forcément être plus tard que la date de départ"));
                }
                if ($data->getStartDate() < $currentDate) {
                    $form->get('startDate')->addError(new FormError("Vous ne pouvez pas réserver pour une date antérieure à celle d'aujourd'hui"));
                }
                if ($data->getNbOfPersons() > $bedRoom->getNbOfPersonsMax()) {
                    $form->get('nbOfPersons')->addError(new FormError("Le nombre de personnes ne peut être supérieur au nombre de places"));
                }
            });
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Reservation::class,
            'bedRoom' => null
        ]);
    }
}
