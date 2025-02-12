<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

final class AuthController extends AbstractController
{
    #[Route('/auth', name: 'app_auth', methods:['GET'])]
    public function index(): Response
    {

        if ($this->getUser()) {
            return $this->redirectToRoute('app_usuario');
        }

        return $this->render('auth/login.html.twig', [
            'last_username' => '',
            'error' => null
        ]);
    }

    #[Route('/login', name: 'app_auth_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        
        if ($this->getUser()) {
            return $this->redirectToRoute('app_usuario');
        }

        return $this->render('auth/login.html.twig', [
            'last_username' => $authenticationUtils->getLastUsername(),
            'error' => $authenticationUtils->getLastAuthenticationError()
        ]);
    }

    #[Route('/auth/register', name: 'app_usuario_resgister', methods:['GET'])]
    public function register()
    {
        return $this->render('auth/register.html.twig');
    }

    
    #[Route('/logout', name: 'app_auth_logout')]
    public function logout(Request $request, TokenStorageInterface $tokenStorage): Response
    {
        $request->getSession()->invalidate();
        $tokenStorage->setToken(null);
        return $this->redirectToRoute('app_auth');
    }
}
