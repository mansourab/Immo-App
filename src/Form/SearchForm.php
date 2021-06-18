<?php

namespace App\Form;

use App\Data\SearchData;
use App\Entity\Quarter;
use App\Entity\Category;
use App\Entity\Type;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchForm extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('q', TextType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'placeholder' => 'Entrer votre recherche'
                ]
            ])
            ->add('categories', EntityType::class, [
                'label' => false,
                'required' => false,
                'class' => Category::class,
                'expanded' => true,
                'multiple' => true
            ])
            ->add('quarter', EntityType::class, [
                'label' => false,
                'required' => false,
                'class' => Quarter::class,
                'placeholder' => 'Choisir un quartier'
            ])
            ->add('type', EntityType::class, [
                'label' => false,
                'required' => false,
                'class' => Type::class,
                'placeholder' => 'Choisir Type de Bien'
                // 'expanded' => true,
                // 'multile' => true
            ])
            // ->add('min', NumberType::class, [
            //     'label' => false,
            //     'required' => false, 
            //     'attr' => [
            //         'placeholder' => 'Prix min'
            //     ]
            // ])
            // ->add('max', NumberType::class, [
            //     'label' => false,
            //     'required' => false,
            //     'attr' => [
            //         'placeholder' => 'Prix max'
            //     ]
            // ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SearchData::class,
            'method' => 'GET',
            'csrf_protection' => false
        ]);
    }
 
    public function getBlockPrefix()
    {
        return '';
    }
}