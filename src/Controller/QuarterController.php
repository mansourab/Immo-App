<?php

namespace App\Controller;


use App\Entity\Quarter;
use App\Form\QuarterFormType;
use App\Repository\QuarterRepository;
use Doctrine\ORM\EntityManagerInterface;
use Flasher\Toastr\Prime\ToastrFactory;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/back-office")
 */
class QuarterController extends AbstractController
{

    private $flasher;


    public function __construct(ToastrFactory $flasher)
    {
        $this->flasher = $flasher;
    }

    /**
     * @Route("/quarter/index", name="app_quarter_index")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(QuarterRepository $repo, PaginatorInterface $paginator, Request $request)
    {
        $quarters = $paginator->paginate(
            $repo->findAll(),
            $request->query->getInt('page', 1),
            8
        );

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

            $this->flasher->addSuccess('Nouveau quartier ajouté avec succès');

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

            $this->flasher->addSuccess('Nouveau quartier édité avec succès');

            return $this->redirectToRoute('app_quarter_index');
        }

        return $this->render('admin/quarter/edit/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/quarter/{id}", name="app_quarter_delete", methods={"POST"})
     */
    public function delete_owner(Request $request, Quarter $quarter): Response
    {
        if ($this->isCsrfTokenValid('delete'.$quarter->getId(), $request->request->get('_token'))) {

            $entityManager = $this->getDoctrine()->getManager();

            $entityManager->remove($quarter);
            
            $entityManager->flush();

            $this->flasher->addSuccess('Quartier ou Lieu supprimé avec succès');
        }

        return $this->redirectToRoute('app_quarter_index');
    }
}