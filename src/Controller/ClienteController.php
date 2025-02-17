<?php

namespace App\Controller;

use App\Entity\Cliente;
use App\Repository\ClienteRepository;
use DateTime;
use DateTimeImmutable;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ClienteController extends AbstractController
{
    public function __construct(readonly EntityManagerInterface $entityManager)
    {
        
    }

    #[Route('/cliente', name: 'app_cliente')]
    public function index( ClienteRepository $clienteRepository): Response
    {

        $qb = $clienteRepository->createQueryBuilder('c')
        ->select('c.id', 'c.Nome', 'c.Contato', 'c.Endereco')
        ->orderBy('c.id', 'DESC');


        return $this->render('cliente/index.html.twig', [
            'cliente'=> $qb->getQuery()->getResult(),
        ]);
    }

    #[Route('/create', name: 'app_cliente_create', methods:['GET'])]
    public function createCliente(){
        return $this->render('cliente/create.html.twig');
    }

    #[Route('/cliente/register', name: 'app_cliente_register', methods:['POST'])]
    public function registerCliente( Request $request, ClienteRepository $clienteRepository): Response
    {
        $post = $request->request->all();
        
        $cliente = new Cliente();
        $cliente->setNome($post['nome']);
        $cliente->setCpf($post['cpf']);
        $cliente->setEndereco($post['endereco']);
        $cliente->setContato($post['contato']);
        $cliente->setCreateAt(new DateTimeImmutable());
        $cliente->setUpdateAt(new DateTime());

        $this->entityManager->persist($cliente);
        $this->entityManager->flush();
        return $this->redirectToRoute('app_cliente');
    }


    
    #[Route('/cliente/{id}', name:'app_cliente_show', methods:['GET'])]
    public function show($id, ClienteRepository $clienteRepository): Response{
        $cliente = $clienteRepository->find($id);
        if (!$cliente) {
           return $this->redirectToRoute('app_cliente');
        }
   
        return $this->render('cliente/edit.html.twig', ['cliente'=>$cliente]);
    }

    #[Route('/cliente/edit', name: 'app_cliente_edit', methods:['POST'])]
    public function editarCliente(Request $request, ClienteRepository $clienteRepository){
    
        $post = $request->request->all();

        $cliente = $clienteRepository->findOneBy(["id"=> $post['inputId']]);
        $cliente->setNome($post['nome']);
        $cliente->setCpf($post['cpf']);
        $cliente->setEndereco($post['endereco']);
        $cliente->setContato($post['contato']);
        $cliente->setUpdateAt(new DateTime());
        
        $this->entityManager->persist($cliente);
        $this->entityManager->flush();
        return $this->redirectToRoute('app_cliente');
    }
}
