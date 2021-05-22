<?php

namespace App\Controller;

use App\Repository\PropertyRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


class AdminController extends AbstractController
{

    /**
     * @Route("/admin/index", name="app_admin_index")
     */
    public function index(PropertyRepository $repo, PaginatorInterface $paginator, Request $request)
    {
        // $properties = $repo->findAll();

        $properties = $paginator->paginate(
            $repo->findAll(),
            $request->query->getInt('page', 1),
            3
        );

        return $this->render('backend/admin/index.html.twig', [
            'properties' => $properties
        ]);
    }
}