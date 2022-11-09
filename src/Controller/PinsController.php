<?php

namespace App\Controller;

use App\Entity\Pin;
use App\Repository\PinRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class PinsController extends AbstractController
{
    #[Route('/pins', name: 'app_pins')]
    public function index(PinRepository $repo): Response
    {
        return $this->render('pins/index.html.twig', [
            'pins' => $repo->findBy([], ['createdAt' => "DESC"]),
        ]);
    }

    #[Route('/pins/create', name: 'app_pins_create')]
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        $pin = new Pin;
        $form = $this->createFormBuilder($pin)
        ->add('title', null, ['attr'=> ['autofocus'=>true]])
        ->add('description', TextareaType::class, ['attr'=> ['rows'=>5, 'cols'=>50]])
        ->getForm()
        ;
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) { 
            $em->persist($pin);
            $em->flush();
            $this->addFlash('success', 'Pin créé avec succès!');
            return $this-> redirectToRoute('app_pins');
        }
        return $this->render('pins/create.html.twig', [
            'monFormulaire' => $form->createView(),
        ]);
    }
    
    #[Route('/pins/{id}', name: 'app_pins_details', priority:-1)]
    public function show(PinRepository $repo, int $id): Response
    {
        $pin = $repo->find($id);
        if(!$pin){
            throw $this->createNotFoundException($id . ' n\'est pas un identifiant de ma Base de données');
        }

        return $this->render('pins/details.html.twig', compact('pin'));
    }

    #[Route('/pins/{id}/edit', name: 'app_pins_edit', methods: ['GET', 'POST'])]
    public function edit(Pin $pin, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createFormBuilder($pin)
        ->add('title', null, ['attr'=> ['autofocus'=>true]])
        ->add('description', TextareaType::class, ['attr'=> ['rows'=>5, 'cols'=>50]])
        ->getForm()
        ;
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) { 
            $em->flush();
            $this->addFlash('success', 'Pin modifié avec succès!');
            return $this-> redirectToRoute('app_pins');
        }
        return $this->render('pins/edit.html.twig', [
            'pin' => $pin,
            'monFormulaire' => $form->createView(),
        ]);
    }

    #[Route('/pins/{id}/delete', name: 'app_pins_delete', methods: ['GET', 'POST', 'DELETE'])]
    public function delete(Pin $pin, EntityManagerInterface $em, Request $request): Response
    {
        $token_valide = $request->request->get('csrf_token');
        if ($this->isCsrfTokenValid('pin_deletion_'. $pin->getId(), $token_valide )){
            $em->remove($pin);
            $em->flush();    
            $this->addFlash('info', 'Pin supprimé avec succès!');   
        }
        return $this-> redirectToRoute('app_pins');
    }

}

