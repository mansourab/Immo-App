<?php

namespace App\Controller;

use App\Data\SearchData;
use App\Entity\Property;
use App\Form\SearchForm;
use App\Repository\PropertyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{

    /**
     * @Route("/", name="app_home_index")
     */
    public function index(PropertyRepository $repo) : Response
    {
        $latestProperties = $repo->FindLatestProperties();

        $latestForRent = $repo->findLatestRent();

        $properties = $repo->findAll();

        return $this->render('frontend/home/index.html.twig', [
            'latest_properties' => $latestProperties,
            'properties' => $properties,
            'latest_rent' => $latestForRent,
        ]);
    }

    /**
     * @Route("/annonce/detail/{id}", name="app_annonce_detail")
     */
    public function detail(Property $property)
    {
        return $this->render('frontend/detail/show.html.twig', [
            'property' => $property
        ]);
    }

    /**
     * @Route("/annonces", name="app_annonce_all")
     * @return Response
     */
    public function properties(PropertyRepository $repo, Request $request): Response
    {
        $data = new SearchData;

        $data->page = $request->get('page', 1);

        $form = $this->createForm(SearchForm::class, $data);
        $form->handleRequest($request);

        $properties = $repo->findSearch($data);


        return $this->render('frontend/properties/index.html.twig', [
            'properties' => $properties,
            'form' => $form->createView(),
        ]);
    }

}