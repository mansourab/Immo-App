<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\CategoryRepository;
use App\Repository\PropertyRepository;
use App\Repository\QuarterRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/back/office")
 */
class AdminController extends AbstractController
{

    private $paginator;

    public function __construct(PaginatorInterface $paginator)
    {
        $this->paginator = $paginator;
    }

    /**
     * @Route("/index", name="app_admin_index")
     * @return Response
     */
    public function index(PropertyRepository $repo, Request $request, CategoryRepository $repoCat, QuarterRepository $repoQt): Response
    {
        $user = new User();

        $properties = $this->paginator->paginate(
            $repo->findActif(),
            $request->query->getInt('page', 1),
            8
        );

        $categories = $repoCat->findAll();

        $quarters = $repoQt->findAll();

        return $this->render('admin/index.html.twig', [
            'properties' => $properties,
            'user' => $user,
            'categories' => $categories,
            'quarters' => $quarters,
        ]);
    }

    /**
     * @Route("/annonces/terminer", name="app_annonce_terminer")
     * @return Response
     */
    public function terminer(PropertyRepository $repo, Request $request): Response
    {
        $terminer = $this->paginator->paginate(
            $repo->findTerminer(),
            $request->query->getInt('page', 1),
            8
        );

        return $this->render('admin/terminer/index.html.twig', [
            'terminer' => $terminer,
        ]);
    }

    /**
     * @Route("/annonces/annuler", name="app_annonce_annuler")
     */
    public function annuler(PropertyRepository $repo, Request $request)
    {
        $annuler = $this->paginator->paginate(
            $repo->findAnnuler(),
            $request->query->getInt('page', 1),
            8
        );

        return $this->render('admin/annuler/index.html.twig', [
            'annuler' => $annuler
        ]);
    }

    /**
     * @Route("/annonces/inactive", name="app_annonce_inactive")
     */
    public function notPublished(PropertyRepository $repo, Request $request)
    {
        $inactives = $this->paginator->paginate(
            $repo->findInactive(),
            $request->query->getInt('page', 1),
            8
        );

        return $this->render('admin/inactive/index.html.twig', [
            'inactives' => $inactives
        ]);
    }

    
}