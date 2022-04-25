<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AnimalerieHomeController extends AbstractController
{
    public EntityManager $entityManager;
    public ProductRepository $productRep;
    public UserRepository $userRep;

    #[Route('/animalerie/home', name: 'app_animalerie_home')]
    public function index(): Response
    {
        return $this->render('animalerie_home/index.html.twig', [
            'controller_name' => 'AnimalerieHomeController',
        ]);
    }
}
