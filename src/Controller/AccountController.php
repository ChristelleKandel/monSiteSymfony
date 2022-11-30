<?php

namespace App\Controller;

use App\Form\AccountFormType;
use App\Form\ChangePasswordFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[Route('/account')]
class AccountController extends AbstractController
{
    #[Route('', name: 'app_account')]
    public function show_account(): Response
    {
        return $this->render('account/show_account.html.twig');
    }

    #[Route('/edit', name: 'app_account_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, EntityManagerInterface $em): Response
    {
        // Je rajoute $user pour pré-remplir le formulaire et associéer les modifications à ce user avant de l'enregistrer
        $user = $this->getUser();
        $form = $this->createForm(AccountFormType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) { 
            $em->flush();
            $this->addFlash('success', 'Compte modifié avec succès!');
            return $this-> redirectToRoute('app_account');
        }
        return $this->render('account/edit_account.html.twig', [
            'monFormulaire' => $form->createView(),
        ]);
    }

    #[Route('/password', name: 'app_account_password', methods: ['GET', 'POST', 'PUT'])]
    public function changePassword(Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $PasswordEncoder): Response
    {
        // Je rajoute $user pour pré-remplir le formulaire et associer les modifications à ce user avant de l'enregistrer
        $user = $this->getUser();
        // createform, hérité de AbstractController, permet de passer (un type, des data, des options). Pour mettre des options et pas de data, on met null en 2eme position, on peut ainsi créer une option de toute pièce, ici un champ par défaut à false et rajouter dans des form si nécessaire. Cette option doit être ajoutée dans notre FormeType tout en bas, dans le configureOptions
        $form = $this->createForm(ChangePasswordFormType::class, null, [
            'current_password_is_required' => true
        ]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) { 
            $user->setPassword(
                $PasswordEncoder->hashPassword($user, $form['newPassword']->getData())
            );
            $em->flush();
            $this->addFlash('success', 'Mot de passe modifié avec succès!');
            return $this-> redirectToRoute('app_account');
        }
        return $this->render('account/change_password.html.twig',[
            'monFormulaire' => $form->createView(),
        ]);
    }
}
