<?php

namespace App\Controller;

use App\Data\SearchData;
use App\Entity\Property;
use App\Form\PropertyFormType;
use App\Form\SearchForm;
use App\Repository\PropertyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

class PropertyController extends AbstractController
{


    /**
     * @Route("/property/list", name="app_property_list")
     */
    public function list_property(PropertyRepository $propertyRepository, PaginatorInterface $paginator, Request $request): Response
    {
        // $properties = $propertyRepository->findAll();

        $properties = $paginator->paginate(
            $propertyRepository->findAll(),
            $request->query->getInt('page', 1),
            3
        );

        // $data = new SearchData;

        // $data->page = $request->get('page', 1);

        // $form = $this->createForm(SearchForm::class, $data);
        // $form->handleRequest($request);

        // $properties = $propertyRepository->findSearch($data);

        return $this->render('property/index.html.twig', [
            'properties' => $properties,
            // 'form' => $form->createView(),
        ]);
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

    // /**
    //  * @Route("/search/results", name="app_search_results")
    //  */
    // public function search_results(PropertyRepository $propertyRepository, PaginatorInterface $paginator, Request $request): Response
    // {
    //     $data = new SearchData;

    //     $data->page = $request->get('page', 1);

    //     $form = $this->createForm(SearchForm::class, $data);
    //     $form->handleRequest($request);

    //     $properties = $propertyRepository->findSearch($data);

    //     return $this->render('search/index.html.twig', [
    //         'properties' => $properties,
    //         'form' => $form->createView(),
    //     ]);
    // }

    // /**
    //  * @Route("/search")
    //  */
    // public function search(Request $request, PropertyRepository $propertyRepository)
    // {
    //     $data = new SearchData;

    //     $data->page = $request->get('page', 1);

    //     $form = $this->createForm(SearchForm::class, $data);
    //     $form->handleRequest($request);

    //     $properties = $propertyRepository->findSearch($data);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         return $this->redirectToRoute('app_search_results');
    //     }

    //     return $this->render('search/init.html.twig', [
    //         'form' => $form->createView(),
    //         'properties' => $properties
    //     ]);
    // }
}
