<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;


final class UsuarioController extends AbstractController
{

    public function  __construct(readonly EntityManagerInterface $entityManager) { }

    #[Route('/usuario', name: 'app_usuario')]
    public function index(UserRepository $userRepository): Response
    {
        //aqui entre () é o alias, é o nome da tabela(as u)
       $qb = $userRepository->createQueryBuilder('u')
        ->select('u.id', 'u.username', 'u.email', 'u.fullName')
        ->orderBy('u.id', 'DESC');
  
        return $this->render('usuario/index.html.twig', [
            'users'=>$qb->getQuery()->getResult(),
        ]);
    }
    #[Route('/create', name: 'app_usuario_create', methods:['GET'])]
    public function create(){
        return $this->render('usuario/create.html.twig');
    }

    #[Route('/usuario/create', name: 'app_usuario_register', methods:['POST'])]
    public function recebeCreate(Request $request, UserPasswordHasherInterface $userPasswordHasher, UserRepository $userRepository ){
        $post = $request->request->all();
        
        $user = new User();
        $user->setEmail($post['email']);
        $user->setUsername($post['username']);
        $user->setFullName($post['fullname']);
        $user->setRoles([]);
        $user->setPassword($userPasswordHasher->hashPassword($user, $post['password']));

        $this->entityManager->persist($user);
        $this->entityManager->flush();
        return $this->redirectToRoute('app_usuario');
    }

    #[Route('/usuario/delete/{id}', name:'app_usuario_delete', methods:['GET'])]
    public function delete($id, UserRepository $userRepository):Response
    {
       $user = $userRepository->findOneBy(["id"=> $id]);
        if (!$user) {
            $this->redirectToRoute('app_usuario', ['error'=>'User not found']);
        }
        $this->entityManager->remove($user);
        $this->entityManager->flush();
        
        return $this->redirectToRoute('app_usuario');
    }

    #[Route('/usuario/show/{id}', name:'app_usuario_show', methods:['GET'])]
    public function show($id, UserRepository $userRepository): Response{
        $user = $userRepository->findOneBy(["id"=> $id]);
        if (!$user) {
            $this->redirectToRoute('app_usuario', ['error'=>'User not found']);
        }
   
        return $this->render('usuario/edit.html.twig', ['user'=>$user]);
    }

    
}
