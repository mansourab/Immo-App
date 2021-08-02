<?php

namespace App\Controller;

use App\Entity\State;
use App\Form\StateFormType;
use App\Repository\StateRepository;
use Doctrine\ORM\EntityManagerInterface;
use Flasher\Toastr\Prime\ToastrFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/back-office")
 */
class StateController extends AbstractController
{

    private $flasher;


    public function __construct(ToastrFactory $flasher)
    {
        $this->flasher = $flasher;
    }

    /**
     * @Route("/state/index", name="app_state_index")
     */
    public function index(StateRepository $repo)
    {
        $states = $repo->findAll();

        return $this->render('admin/state/list/index.html.twig', compact('states'));
    }

    /**
     * @Route("/state/new", name="app_state_new")
     */
    public function new(EntityManagerInterface $em, Request $request): Response
    {
        $state = new State;

        $form = $this->createForm(StateFormType::class, $state);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($state);

            $em->flush();

            $this->flasher->addSuccess('Région ajoutée avec succès');

            return $this->redirectToRoute('app_state_index');
        }

        return $this->render('admin/state/create/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/state/edit/{id}", name="app_state_edit")
     */
    public function edit(State $state, Request $request, EntityManagerInterface $em)
    {
        $form = $this->createForm(StateFormType::class, $state);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($state);

            $em->flush();

            return $this->redirectToRoute('app_state_index');
        }

        return $this->render('admin/state/edit/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/state/{id}", name="app_state_delete", methods={"POST"})
     */
    public function delete_category(Request $request, State $state)
    {
        if ($this->isCsrfTokenValid('delete'.$state->getId(), $request->request->get('_token'))) {

            $entityManager = $this->getDoctrine()->getManager();

            $entityManager->remove($state);
            
            $entityManager->flush();

            $this->flasher->addSuccess('région de bien supprimé avec succès.');

            return $this->redirectToRoute('app_state_index');
        }
    }
}