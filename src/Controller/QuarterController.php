<?php

namespace App\Controller;


use App\Entity\Quarter;
use App\Form\QuarterFormType;
use App\Repository\QuarterRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/back-office")
 */
class QuarterController extends AbstractController
{

    /**
     * @Route("/quarter/index", name="app_quarter_index")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(QuarterRepository $repo)
    {
        $quarters = $repo->findAll();

        return $this->render('admin/quarter/list/index.html.twig', compact('quarters'));
    }

    /**
     * @Route("/quarter/add", name="app_quarter_new")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function new(Request $request, EntityManagerInterface $em)
    {
        $quarter = new Quarter;

        $form = $this->createForm(QuarterFormType::class, $quarter);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($quarter);
            $em->flush();

            return $this->redirectToRoute('app_quarter_index');
        }

        return $this->render('admin/quarter/create/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/quarter/edit/{id}", name="app_quarter_edit")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function edit(Quarter $quarter, Request $request, EntityManagerInterface $em)
    {
        $form = $this->createForm(QuarterFormType::class, $quarter);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em->flush();

            return $this->redirectToRoute('app_quarter_index');
        }

        return $this->render('admin/quarter/edit/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}