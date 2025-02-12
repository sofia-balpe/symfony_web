<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_usuario_quantidade', methods: ['GET'])]
    public function quantidade(UserRepository $userRepository): Response
    {

        return $this->render('home/index.html.twig', [
            'quant' =>  $userRepository->count(),
        ]);
    }
}
