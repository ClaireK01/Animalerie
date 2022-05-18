<?php

namespace App\Controller;

use App\Repository\BrandRepository;
use App\Repository\ProductRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AnimalerieHomeController extends AbstractController
{

    public function __construct(
        private EntityManagerInterface $em,
        private ProductRepository $productRepository,
        private UserRepository $userRepository,
        private BrandRepository $brandRepository
    ){}

    #[Route('/', name: 'app_animalerie_home')]
    public function index(): Response
    {
        $products = $this->productRepository->getLastProduct();
        $bargains = $this->productRepository->getCheapestProduct();
        $bestSellsEntities = $this->productRepository->getBestSells();
        $mostPopularBrands = $this->brandRepository->getMostPopularBrands();
        // dd($mostPopularBrands);

        return $this->render('animalerie_home/index.html.twig', [
            'lastProducts'=>$products,
            'bargains'=>$bargains,
            'bestSells'=>$bestSellsEntities,
            'brands'=> $mostPopularBrands
        ]);
    }

}
