<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class UsuarioController extends AbstractController
{
    #[Route('/usuario', name: 'app_usuario')]
    public function index(): Response
    {
        return $this->render('usuario/index.html.twig', [
            'users' => [
                ['id' => 1, 'Username'=>'sofia', 'Email' => 'sofia@gmail.com', 'fullname'=>'Sofi Baldissera' ],
                ['id' => 2, 'Username'=>'sofia', 'Email' => 'sofia@gmail.com', 'fullname'=>'Sofi Baldissera']
            ]

        ]);
    }
    #[Route('/create', name: 'app_usuario_create', methods:['GET'])]
    public function create(){
        return $this->render('usuario/create.html.twig');
    }

    #[Route('/usuario/create', name: 'app_usuario_register', methods:['POST'])]
    public function recebeCreate(Request $request){
        $post = $request->request->all();
        dd($post);
        return $this->render('usuario/create.html.twing');
    }
}
