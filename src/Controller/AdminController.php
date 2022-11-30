<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin')]

class AdminController extends AbstractController
{
    #[Route('', name: 'app_admin')]
    public function index(): Response
    {
        // accés réservés pour cette action:
        $this-> denyAccessUnlessGranted(attribute:'ROLE_ADMIN', message:'accès interdit');
        return $this->render('admin/index.html.twig');
    }
    #[Route('/pins', name: 'app_admin_pins')]
    public function pins(): Response
    {
        // accés réservés pour cette action:
        // $this-> denyAccessUnlessGranted(attribute:'ROLE_ADMIN', message:'accès interdit');
        return $this->render('admin/pins.html.twig');
    }
}
