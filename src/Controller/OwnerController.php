<?php

namespace App\Controller;

use App\Entity\Owner;
use App\Form\OwnerType;
use App\Repository\OwnerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class OwnerController extends AbstractController
{
    /**
     * @Route("owner/index", name="app_owner_index", methods={"GET"})
     */
    public function index(OwnerRepository $ownerRepository): Response
    {
        return $this->render('admin/owner/list/index.html.twig', [
            'owners' => $ownerRepository->findAll(),
        ]);
    }

    /**
     * @Route("owner/new", name="app_owner_new", methods={"GET", "POST"})
     */
    public function new_owner(Request $request): Response
    {
        $owner = new Owner;

        $form = $this->createForm(OwnerType::class, $owner);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($owner);
            $entityManager->flush();

            return $this->redirectToRoute('app_owner_index');
        }

        return $this->render('admin/owner/create/index.html.twig', [
            'owner' => $owner,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("owner/{id}/edit", name="app_owner_edit", methods={"GET","POST"})
     */
    public function edit_owner(Request $request, Owner $owner): Response
    {
        $form = $this->createForm(OwnerType::class, $owner);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('app_owner_index');
        }

        return $this->render('admin/owner/edit/index.html.twig', [
            'owner' => $owner,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("admin/owner/{id}", name="app_owner_delete", methods={"POST"})
     */
    public function delete_owner(Request $request, Owner $owner): Response
    {
        if ($this->isCsrfTokenValid('delete'.$owner->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($owner);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_owner_index');
    }
}
