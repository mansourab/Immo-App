<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Owner;
use App\Entity\Property;
use App\Entity\Quarter;
use App\Entity\Type;
use Doctrine\DBAL\Types\BooleanType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
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
                'label' => 'Property Title'
            ])
            ->add('content', TextareaType::class, [
                'required' => false,
                'label' => 'Property Description'
            ])
            ->add('imageFile', VichImageType::class, [
                'label' => 'Cover Image (png, jpeg, jpg)',
                'required' => false,
                'allow_delete' => false,
                'download_uri' => false,
                'image_uri' => false,
                'asset_helper' => false,
            ])
            ->add('categories', EntityType::class, [
                'required' => false,
                'label' => 'Choose Categories',
                'class' => Category::class,
//                'expanded' => true,
                'multiple' => true,
            ])
            ->add('quarter', EntityType::class, [
                'required' => false,
                'label' => 'Lieu',
                'class' => Quarter::class,

            ])
            ->add('owner', EntityType::class, [
                'required' => false,
                'label' => 'Property Owner',
                'class' => Owner::class
            ])
            ->add('images', FileType::class, [
                'label' => 'Upload Files',
                'multiple' => true,
                'mapped' => false,
                'required' => false
            ])
            ->add('price', NumberType::class, [
                'label' => 'Property Price',
                'required' => false,
            ])
            ->add('area', NumberType::class, [
                'label' => 'Property Area',
                'required' => false,
            ])
            ->add('room', NumberType::class, [
                'label' => 'Property Rooms',
                'required' => false,
            ])
            ->add('type', EntityType::class, [
                'required' => false,
                'label' => 'Property Type',
                'class' => Type::class
            ])
            ->add('status', TextType::class, [
                'required' => false,
                'label' => 'Property Status'
            ])
//            ->add('published', CheckboxType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Property::class,
        ]);
    }
}
