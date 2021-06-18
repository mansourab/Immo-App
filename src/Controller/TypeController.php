<?php

namespace App\Controller;

use App\Entity\Type;
use App\Form\TypeFormType;
use App\Repository\TypeRepository;
use Doctrine\DBAL\Types\TypeRegistry;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class TypeController extends AbstractController
{

    /**
     * @Route("/admin/type/index", name="app_type_list")
     */
    public function index(TypeRepository $repository)
    {
        $types = $repository->findAll();

        return $this->render('backend/admin/type/index.html.twig', [
            'types' => $types,
        ]);
    }

    /**
     * @Route("/admin/type/new", name="app_type_new")
     */
    public function new(EntityManagerInterface $em, Request $request): Response
    {
        $type = new Type;

        $form = $this->createForm(TypeFormType::class, $type);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($type);

            $em->flush();

            return $this->redirectToRoute('app_type_list');
        }

        return $this->render('backend/admin/type/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/type/edit/{id}", name="app_type_edit")
     */
    public function edit(Type $type, Request $request, EntityManagerInterface $em)
    {
        $form = $this->createForm(TypeFormType::class, $type);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($type);

            $em->flush();

            return $this->redirectToRoute('app_type_list');
        }

        return $this->render('backend/admin/type/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}