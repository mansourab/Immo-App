<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\CategoryRepository;
use App\Repository\PropertyRepository;
use App\Repository\QuarterRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/back-office")
 */
class AdminController extends AbstractController
{

    /**
     * @Route("/index", name="app_admin_index")
     * @return Response
     */
    public function index(PropertyRepository $repo, PaginatorInterface $paginator, Request $request, CategoryRepository $repoCat, QuarterRepository $repoQt): Response
    {
        $user = new User();

        $properties = $paginator->paginate(
            $repo->findAll(),
            $request->query->getInt('page', 1),
            3
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
    public function terminer(PropertyRepository $repo, EntityManagerInterface $em): Response
    {
        // $terminer = $repo->findEnd();
        $terminer = $repo->findAll();

        // $query = $em->createQuery('SELECT status FROM App\Entity\Property WHERE status = Inactif');
        // $inactif = $query->getResult();

        return $this->render('admin/terminer/index.html.twig', [
            'terminer' => $terminer,
        ]);
    }
}