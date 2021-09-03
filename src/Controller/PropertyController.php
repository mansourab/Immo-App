<?php

namespace App\Controller;


use App\Entity\Image;
use App\Entity\Property;
use App\Form\PropertyFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Flasher\Toastr\Prime\ToastrFactory;

/**
 * @Route("/back/office")
 */
class PropertyController extends AbstractController
{

    /**
     * @var EntityManagerInterface
     */
    private $manager;

    private $flasher;


    public function __construct(EntityManagerInterface $manager, ToastrFactory $flasher)
    {
        $this->manager = $manager;
        $this->flasher = $flasher;
    }

    /**
     * @Route("/property/new", name="app_property_new", methods={"GET", "POST"})
     * 
     * @return Symfony\Component\HttpFoundation\Response
     */
    public function new_property(Request $request): Response
    {
        $property = new Property;

        $user = $this->getUser();

        $form = $this->createForm(PropertyFormType::class, $property);
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $images = $form->get('images')->getData();

           
            foreach($images as $image) {
                
                $fichier = md5(uniqid()). '.' . $image->guessExtension();
               
                $image->move(
                    $this->getParameter('images_galerie'),
                    $fichier
                );
                
                $img = new Image;

                $img->setUrl($fichier);

                $property->addImage($img);

            }

            $property->setUser($user);

            $this->manager->persist($property);

            $this->manager->flush();

            
            $this->flasher->addSuccess('Annonce ajouté avec succès');

            return $this->redirectToRoute('app_admin_index');
        }

        return $this->render('admin/property/create/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/property/edit/{id}", name="app_property_edit")
     * @param Symfony\Component\HttpFoundation\Request
     * @param App\Entity\Property
     */
    public function edit_property(Property $property, Request $request): Response
    {

        $form = $this->createForm(PropertyFormType::class, $property);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            
            $images = $form->get('images')->getData();

            
            foreach($images as $image) {
                
                $fichier = md5(uniqid()). '.' . $image->guessExtension();
                
                $image->move(
                    $this->getParameter('images_galerie'),
                    $fichier
                );
                
                $img = new Image;
                $img->setUrl($fichier);
                $property->addImage($img);
            }

            $this->manager->flush();

            
            $this->flasher->addSuccess('Annonce édité avec succès');

            return $this->redirectToRoute('app_admin_index');
        }

        return $this->render('admin/property/edit/index.html.twig', [
            'property' => $property,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/property/img/delete/{id}", name="app_galerie_delete", methods={"DELETE"})
     */
    public function delete_image(Image $image, Request $request)
    {
        $data = json_decode($request->getContent(), true);

        if ($this->isCsrfTokenValid('delete'.$image->getId(), $data['_token'])) {
            $url = $image->getUrl();

            unlink($this->getParameter('images_galerie').'/'.$url);

            $em = $this->getDoctrine()->getManager();

            $em->remove($image);

            $em->flush();

            return new JsonResponse(['success' => 1]); 

        } else {
            return new JsonResponse(['error' => 'Token invalid'], 400);
        }
    }


    /**
     * @Route("/property/{id}", name="app_property_delete", methods={"POST"})
     */
    public function delete_property(Request $request, Property $property)
    {
        if ($this->isCsrfTokenValid('delete'.$property->getId(), $request->request->get('_token'))) {

            $entityManager = $this->getDoctrine()->getManager();

            $entityManager->remove($property);

            $entityManager->flush();

            $this->flasher->addSuccess('Annonce supprimé avec succès.');

            return $this->redirectToRoute('app_admin_index');
        }

        $this->flasher->error('Oops! une erreur s\'est produite.');
        
    }

}
