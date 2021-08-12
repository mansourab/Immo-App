<?php

namespace App\Controller;

use App\Entity\Owner;
use App\Form\OwnerType;
use App\Repository\OwnerRepository;
use Flasher\Toastr\Prime\ToastrFactory;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/back/office")
 */
class OwnerController extends AbstractController
{
    /**
     * @var ToastrFactory
     */
    private $flasher;


    public function __construct(ToastrFactory $flasher)
    {
        $this->flasher = $flasher;
    }

    /**
     * @Route("/owner/index", name="app_owner_index", methods={"GET"})
     */
    public function index(OwnerRepository $ownerRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $owners = $paginator->paginate(
            $ownerRepository->findAll(),
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('admin/owner/list/index.html.twig', [
            'owners' => $owners
        ]);
    }

    /**
     * @Route("/owner/new", name="app_owner_new", methods={"GET", "POST"})
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

            $this->flasher->addSuccess('Nouvel Item ajouté avec succès');

            return $this->redirectToRoute('app_owner_index');
        }

        return $this->render('admin/owner/create/index.html.twig', [
            'owner' => $owner,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/owner/{id}/edit", name="app_owner_edit", methods={"GET","POST"})
     */
    public function edit_owner(Request $request, Owner $owner): Response
    {
        $form = $this->createForm(OwnerType::class, $owner);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->getDoctrine()->getManager()->flush();

            $this->flasher->addSuccess('Item édité avec succès');

            return $this->redirectToRoute('app_owner_index');
        }

        return $this->render('admin/owner/edit/index.html.twig', [
            'owner' => $owner,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/owner/{id}", name="app_owner_delete", methods={"POST"})
     */
    public function delete_owner(Request $request, Owner $owner): Response
    {
        if ($this->isCsrfTokenValid('delete'.$owner->getId(), $request->request->get('_token'))) {

            $entityManager = $this->getDoctrine()->getManager();

            $entityManager->remove($owner);
            
            $entityManager->flush();

            $this->flasher->addSuccess('Item supprimé avec succès');
        }

        return $this->redirectToRoute('app_owner_index');
    }
}
