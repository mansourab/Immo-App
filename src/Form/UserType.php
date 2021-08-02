<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('name', TextType::class, [
            'required' => false,
            'label' => 'Nom et Prénom'
        ])
        ->add('email', EmailType::class, [
            'required' => false,
            'label' => 'Email'
        ])
        ->add('avatarFile', VichImageType::class, [
            'label' => 'Avatar',
            'required' => false,
            'allow_delete' => false,
            'download_uri' => false,
            'image_uri' => false,
            'asset_helper' => false,
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
