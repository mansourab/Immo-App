<?php

namespace App\Controller;

use App\Entity\Type;
use App\Form\TypeFormType;
use App\Repository\TypeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Flasher\Toastr\Prime\ToastrFactory;


/**
 * @Route("/back-office")
 */
class TypeController extends AbstractController
{

    private $flasher;


    public function __construct(ToastrFactory $flasher)
    {
        $this->flasher = $flasher;
    }


    /**
     * @Route("/type/index", name="app_type_index")
     */
    public function index(TypeRepository $repository)
    {
        $types = $repository->findAll();

        return $this->render('admin/type/list/index.html.twig', [
            'types' => $types,
        ]);
    }

    /**
     * @Route("/type/new", name="app_type_new")
     */
    public function new(EntityManagerInterface $em, Request $request): Response
    {
        $type = new Type;

        $form = $this->createForm(TypeFormType::class, $type);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($type);

            $em->flush();

            $this->flasher->addSuccess('Type ajouté avec succès.');

            return $this->redirectToRoute('app_type_index');
        }

        return $this->render('admin/type/create/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/type/edit/{id}", name="app_type_edit")
     */
    public function edit(Type $type, Request $request, EntityManagerInterface $em)
    {
        $form = $this->createForm(TypeFormType::class, $type);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($type);

            $em->flush();

            $this->flasher->addSuccess('Type de bien edité avec succès.');

            return $this->redirectToRoute('app_type_index');
        }

        return $this->render('admin/type/edit/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/type/{id}", name="app_type_delete", methods={"POST"})
     */
    public function delete_category(Request $request, Type $type)
    {
        if ($this->isCsrfTokenValid('delete'.$type->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($type);
            $entityManager->flush();

            $this->flasher->addSuccess('Type de bien supprimé avec succès.');

            return $this->redirectToRoute('app_type_index');
        }

        $this->flasher->error('Oops! une erreur s\'est produite.');
        
    }
}