<?php

namespace App\Controller;

use App\Data\SearchData;
use App\Entity\Property;
use App\Form\SearchForm;
use App\Repository\PropertyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class DefaultController extends AbstractController
{

    /**
     * @Route("/", name="app_home")
     */
    public function index(PropertyRepository $repo) : Response
    {
        $latestProperties = $repo->FindLatestProperties();

        $latestForRent = $repo->findLatestRent();

        $latestForSale = $repo->findLatestSale();

        $featured = $repo->findFeatured();


        $niamey = $repo->findNiamey();
        $dosso = $repo->findDosso();
        $tillaberi = $repo->findTillaberi();
        $tahoua = $repo->findTahoua();
        $agadez = $repo->findAgadez();
        $zinder = $repo->findZinder();
        $maradi = $repo->findMaradi();
        $diffa = $repo->findDiffa();

        return $this->render('home/index.html.twig', [
            'latest_properties' => $latestProperties,
            'latest_sale' => $latestForSale,
            'latest_rent' => $latestForRent,
            'featured' => $featured,
            'niamey' => $niamey,
            'dosso' => $dosso,
            'tillaberi' => $tillaberi,
            'tahoua' => $tahoua,
            'agadez' => $agadez,
            'zinder' => $zinder,
            'maradi' => $maradi,
            'diffa' => $diffa,
        ]);
    }


    /**
     * @Route("/annonce/detail/{slug}", name="app_annonce_detail")
     */
    public function detail(Property $property, PropertyRepository $repo)
    {

        $properties = $repo->findAll();

        return $this->render('detail/index.html.twig', [
            'property' => $property,
            'properties' => $properties
        ]);
    }


    /**
     * @Route("/annonces-immobilieres", name="app_annonces")
     * @return Response
     */
    public function list(PropertyRepository $repo, Request $request): Response
    {
        $data = new SearchData;

        $data->page = $request->get('page', 1);

        $form = $this->createForm(SearchForm::class, $data);

        $form->handleRequest($request);

        $properties = $repo->findSearch($data);

        if ($request->get('ajax')) {
            return new JsonResponse([
                'content' => $this->renderView('list/content/card.html.twig', ['properties' => $properties]),
                // 'sorting' => $this->renderView('list/content/sorting.html.twig', ['properties' => $properties]),
                'pagination' => $this->renderView('list/content/pagination.html.twig', ['properties' => $properties])
            ]);
        }

        return $this->render('list/index.html.twig', [
            'properties' => $properties,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/a-propos", name="app_about")
     * @return Response
     */
    public function about(PropertyRepository $repo): Response
    {
        $envente = $repo->numberSale();

        $enlocation = $repo->numberRent();
        

        return $this->render('about/index.html.twig', [
            'envente' => $envente,
            'enlocation' => $enlocation,
        ]);
    }

    /**
     * @Route("/contact", name="app_contact")
     * @return Response
     */
    public function contact()
    {
        return $this->render('contact/index.html.twig');
    }

    /**
     * @Route("/coming-soon", name="app_coming_soon")
     * @return Response
     */
    public function coming()
    {
        return $this->render('soon/index.html.twig');
    }

}