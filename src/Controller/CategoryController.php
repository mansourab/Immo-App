<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryFormType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{

    /**
     * @Route("/category/index", name="app_category_list")
     */
    public function list_category(CategoryRepository $categoryRepository)
    {
        $categories = $categoryRepository->findAll();

        return $this->render('category/index.html.twig', compact('categories'));
    }

    /**
     * @Route("/category/new", name="app_category_new")
     */
    public function new_category(Request $request, EntityManagerInterface $manager)
    {
        $category = new Category;

        $category_create_form = $this->createForm(CategoryFormType::class, $category);
        $category_create_form->handleRequest($request);

        if ($category_create_form->isSubmitted() && $category_create_form->isValid()) {
            $manager->persist($category);
            $manager->flush();

            $this->addFlash('success', 'The category is added successfully');
            return $this->redirectToRoute('app_category_list');
        }

        return $this->render('category/create.html.twig', [
            'category_create_form' => $category_create_form->createView()
        ]);
    }
}