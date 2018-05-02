<?php

namespace App\Form;

use App\Entity\Hotel;
use App\Entity\PostalCode;
use App\Repository\CityRepository;
use App\Repository\DepartmentRepository;
use App\Repository\PostalCodeRepository;
use App\Repository\RegionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\ChoiceList\Loader\CallbackChoiceLoader;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class HotelType extends AbstractType
{

    private $postalCodeRepository;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->postalCodeRepository = $entityManager->getRepository(PostalCode::class);
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'register.name'
            ])
            ->add('address', TextType::class, [
                'label' => 'register.address'
            ])
            ->add('email', EmailType::class, [
                'label' => 'register.email'
            ])
            ->add('description', TextType::class, [
                'label' => 'register.description'
            ])
            ->add('region', EntityType::class, [
                'label' => 'Région',
                'class' => 'App\Entity\Region',
                'query_builder' => function (RegionRepository $rr) {
                    return $rr->createQueryBuilder('r')
                        ->orderBy('r.name', 'ASC')
                        ;
                },
                'choice_label' => 'name'
            ])
            ->add('department', EntityType::class, [
                'label' => 'Département',
                'class' => 'App\Entity\Department',
                'query_builder' => function (DepartmentRepository $dr) {
                    return $dr->createQueryBuilder('d')
                        ->addSelect('region')
                        ->join('d.region', 'region')
                        ->where("region.slug = 'nouvelle-aquitaine'")
                        ->orderBy('d.name', 'ASC')
                        ;
                },
                'choice_label' => 'name'
            ])
            ->add('city', EntityType::class, [
                'label' => 'Ville',
                'class' => 'App\Entity\City',
                'query_builder' => function (CityRepository $cr) {
                    return $cr->findCitiesFromAquitaine()
                        ;
                },
                'choice_label' => 'name'
            ])
            ->add('postalCode', EntityType::class, [
                'label' => 'Code postal',
                'class' => 'App\Entity\PostalCode',
                'query_builder' => function (PostalCodeRepository $pcr) {
                    return $pcr->findPostalCodesFromAquitaine();
                },
                'choice_label' => 'code'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // uncomment if you want to bind to a class
            'data_class' => Hotel::class
        ]);
    }
}
