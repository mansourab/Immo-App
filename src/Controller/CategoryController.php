<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryFormType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Flasher\Toastr\Prime\ToastrFactory;

/**
 * @Route("/back-office")
 */
class CategoryController extends AbstractController
{

    private $flasher;

    public function __construct(ToastrFactory $flasher)
    {
        $this->flasher = $flasher;
    }


    /**
     * @Route("/category/index", name="app_category_index")
     */
    public function list_category(CategoryRepository $categoryRepository)
    {
        $categories = $categoryRepository->findAll();

        return $this->render('admin/category/list/index.html.twig', compact('categories'));
    }

    /**
     * @Route("/category/new", name="app_category_new")
     */
    public function new_category(Request $request, EntityManagerInterface $manager)
    {
        $category = new Category;

        $form = $this->createForm(CategoryFormType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($category);
            $manager->flush();

            $this->addFlash('success', 'The category is added successfully');

            return $this->redirectToRoute('app_category_index');
        }

        return $this->render('admin/category/create/index.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/category/edit/{id}", name="app_category_edit")
     */
    public function edit_category(Request $request, EntityManagerInterface $manager, Category $category)
    {
        $form = $this->createForm(CategoryFormType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->flush();

            $this->addFlash('success', 'The category is updated successfully');

            return $this->redirectToRoute('app_category_index');
        }

        return $this->render('admin/category/edit/index.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/category/{id}", name="app_category_delete", methods={"POST"})
     */
    public function delete_category(Request $request, Category $category)
    {
        if ($this->isCsrfTokenValid('delete'.$category->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($category);
            $entityManager->flush();

            $this->flasher->addSuccess('Catégorie supprimé avec succès.');

            return $this->redirectToRoute('app_category_index');
        }

        $this->flasher->error('Oops! une erreur s\'est produite.');
        
    }
    
}