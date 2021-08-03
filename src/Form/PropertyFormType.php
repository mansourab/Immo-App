<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Owner;
use App\Entity\Property;
use App\Entity\Quarter;
use App\Entity\State;
use App\Entity\Type;
use Doctrine\DBAL\Types\BooleanType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

class PropertyFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'required' => false,
                'label' => 'Titre de l\'annonce'
            ])
            ->add('content', TextareaType::class, [
                'required' => false,
                'label' => 'Description'
            ])
            ->add('imageFile', VichImageType::class, [
                'label' => 'Image de couverture (png, jpeg, jpg)',
                'required' => false,
                'allow_delete' => false,
                'download_uri' => false,
                'image_uri' => false,
                'asset_helper' => false,
            ])
            ->add('categories', EntityType::class, [
                'required' => false,
                'label' => 'Catégories',
                'class' => Category::class,
//                'expanded' => true,
                'multiple' => true,
            ])
            ->add('quarter', EntityType::class, [
                'required' => false,
                'label' => 'Quartier ou Lieu',
                'class' => Quarter::class,

            ])
            ->add('owner', EntityType::class, [
                'required' => false,
                'label' => 'Propriétaire',
                'class' => Owner::class
            ])
            ->add('images', FileType::class, [
                'label' => 'Télécharger des images (12)',
                'multiple' => true,
                'mapped' => false,
                'required' => false
            ])
            ->add('price', NumberType::class, [
                'label' => 'Prix',
                'required' => false,
            ])
            ->add('area', NumberType::class, [
                'label' => 'Surface',
                'required' => false,
            ])
            ->add('room', NumberType::class, [
                'label' => 'Chambres',
                'required' => false,
            ])
            ->add('types', EntityType::class, [
                'required' => false,
                'label' => 'Type de bien',
                'class' => Type::class,
                'multiple' => true
            ])
            ->add('status', ChoiceType::class, [
                'choices' => [
                    'Actif' => 'Actif',
                    'Annuler' => 'Annuler',
                    'Terminer' => 'Terminer'
                ]
            ])
            ->add('state', EntityType::class, [
                'required' => false,
                'label' => 'Région',
                'class' => State::class
            ])
            ->add('featured', CheckboxType::class, [
                'required' => false,
                'label' => 'VIP',
            ])
           ->add('published', CheckboxType::class, [
                'required' => false,
                'label' => 'Publier',
           ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Property::class,
        ]);
    }
}
