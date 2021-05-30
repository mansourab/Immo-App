<?php

namespace App\Controller;

use App\Data\SearchData;
use App\Entity\Image;
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
use Symfony\Component\HttpFoundation\JsonResponse;

class PropertyController extends AbstractController
{
    /**
     * @Route("/property/list", name="app_property_list")
     */
    public function list_property(PropertyRepository $propertyRepository, PaginatorInterface $paginator, Request $request): Response
    {

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
     * @Route("/property/new", name="app_property_new", methods={"GET", "POST"})
     */
    public function new_property(Request $request, EntityManagerInterface $manager): Response
    {
        $property = new Property;

        $property_create_form = $this->createForm(PropertyFormType::class, $property);
        $property_create_form->handleRequest($request);

        if ($property_create_form->isSubmitted() && $property_create_form->isValid()) {
            // Upload Image multiple
            $images = $property_create_form->get('images')->getData();

            // on boucle sur les images
            foreach($images as $image) {
                // on recupere un nouveau nom de fichier
                $fichier = md5(uniqid()). '.' . $image->guessExtension();
                // On copie le fichier dans le dosiier uploads
                $image->move(
                    $this->getParameter('images_galerie'),
                    $fichier
                );
                // On stock l'image dans la base de donnée
                $img = new Image;
                $img->setUrl($fichier);
                $property->addImage($img);
            }

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
    public function edit_property(Property $property, Request $request, EntityManagerInterface $manager, PropertyRepository $repo): Response
    {

        $property_edit_form = $this->createForm(PropertyFormType::class, $property);
        $property_edit_form->handleRequest($request);

        if ($property_edit_form->isSubmitted() && $property_edit_form->isValid()) {

            // Upload Image multiple
            $images = $property_edit_form->get('images')->getData();

            // on boucle sur les images
            foreach($images as $image) {
                // on recupere un nouveau nom de fichier
                $fichier = md5(uniqid()). '.' . $image->guessExtension();
                // On copie le fichier dans le dosiier uploads
                $image->move(
                    $this->getParameter('images_galerie'),
                    $fichier
                );
                // On stock l'image dans la base de donnée
                $img = new Image;
                $img->setUrl($fichier);
                $property->addImage($img);
            }

            $manager->flush();

            $this->addFlash('success', 'Your property is updated successfully');
            return $this->redirectToRoute('app_property_list');
        }

        return $this->render('property/edit.html.twig', [
            'property' => $property,
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

    /**
     * @Route("/property/img/delete/{id}", name="app_property_galerie", methods={"DELETE"})
     */
    public function deleteImage(Image $image, Request $request)
    {
        $data = json_decode($request->getContent(), true);

        // On verifie si le token est valide
        // On recupere le nom de l'image
        // On supprime l'image
        if ($this->isCsrfTokenValid('delete'.$image->getId(), $data['_token'])) {
            $url = $image->getUrl();

            unlink($this->getParameter('images_galerie').'/'.$url);

            $em = $this->getDoctrine()->getManager();
            $em->remove($image);
            $em->flush();

            // On repond en JSON
            return new JsonResponse(['success' => 1]); 

        } else {
            return new JsonResponse(['error' => 'Token invalid'], 400);
        }
    }


    /**
     * @Route("/search/results", name="app_search_results")
     */
    public function search_results(PropertyRepository $repo, PaginatorInterface $paginator, Request $request): Response
    {
        $data = new SearchData;

        $data->page = $request->get('page', 1);

        $form = $this->createForm(SearchForm::class, $data);
        $form->handleRequest($request);

        // [$min, $max] = $repo->findMinMax($data);

        $properties = $repo->findSearch($data);

        return $this->render('search/index.html.twig', [
            'properties' => $properties,
            'form' => $form->createView(),
            // 'min' => $min,
            // 'max' => $max
        ]);
    }

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
