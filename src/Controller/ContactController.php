<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use App\Repository\ContactRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class ContactController extends AbstractController
{
    /**
     * @Route("/contacts", name="app_contact_index", methods={"GET"})
     */
    public function index(ContactRepository $contactRepository): Response
    {
        return $this->render('admin/contact/list/index.html.twig', [
            'contacts' => $contactRepository->findAll(),
        ]);
    }

    /**
     * @Route("contact/new", name="app_contact_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($contact);
            $entityManager->flush();

            return $this->redirectToRoute('app_contact_index');
        }

        return $this->render('admin/contact/create/index.html.twig', [
            'contact' => $contact,
            'form' => $form->createView(),
        ]);
    }

//    /**
//     * @Route("contact/{id}", name="app_contact_show", methods={"GET"})
//     */
//    public function show(Contact $contact): Response
//    {
//        return $this->render('admin/contact/show.html.twig', [
//            'contact' => $contact,
//        ]);
//    }

    /**
     * @Route("contact/edit/{id}", name="app_contact_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Contact $contact): Response
    {
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('app_contact_index');
        }

        return $this->render('admin/contact/edit/index.html.twig', [
            'contact' => $contact,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("contact/{id}", name="app_contact_delete", methods={"POST"})
     */
    public function delete(Request $request, Contact $contact): Response
    {
        if ($this->isCsrfTokenValid('delete'.$contact->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($contact);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_contact_index');
    }
}
