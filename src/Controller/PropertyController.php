<?php

namespace App\Controller;

use App\Entity\Property;
use App\Form\PropertyFormType;
use App\Repository\PropertyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PropertyController extends AbstractController
{


    /**
     * @Route("/property/list", name="app_property_list")
     */
    public function list_property(PropertyRepository $propertyRepository): Response
    {
        $properties = $propertyRepository->findAll();

        return $this->render('property/index.html.twig', compact('properties'));
    }


    /**
     * @Route("/property/new", name="app_property_new")
     */
    public function new_property(Request $request, EntityManagerInterface $manager): Response
    {
        $property = new Property;

        $property_create_form = $this->createForm(PropertyFormType::class, $property);
        $property_create_form->handleRequest($request);

        if ($property_create_form->isSubmitted() && $property_create_form->isValid()) {
            $manager->persist($property);
            $manager->flush();

            $this->addFlash('success', 'Your property is added successfully');

            return $this->redirectToRoute('app_property_list');
        }

        return $this->render('property/create.html.twig', [
            'property_create_form' => $property_create_form->createView(),
        ]);
    }


    /**
     * @Route("/property/edit/{id}", name="app_property_edit")
     */
    public function edit_property(Property $property, Request $request, EntityManagerInterface $manager): Response
    {
        $property_edit_form = $this->createForm(PropertyFormType::class, $property);
        $property_edit_form->handleRequest($request);

        if ($property_edit_form->isSubmitted() && $property_edit_form->isValid()) {
            $manager->flush();

            $this->addFlash('success', 'Your property is added successfully');

            return $this->redirectToRoute('app_property_list');
        }

        return $this->render('property/edit.html.twig', [
            'property_edit_form' => $property_edit_form->createView(),
        ]);
    }

    /**
     * @Route("/property/show/{id}", name="app_property_show")
     */
    public function show_property(Property $property)
    {
        return $this->render('property/show.html.twig', [
            'property' => $property
        ]);
    }
}
