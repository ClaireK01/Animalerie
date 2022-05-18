<?php

use App\Entity\Command;
use App\Entity\Product;
use App\Repository\CommandRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BasketService extends AbstractController{

    private function __construct(
        private CommandRepository $commandRepository,
        private EntityManagerInterface $em,
    ){}
    

    // Fonction qui créer un panier
    public function getBasket($user){

        $user = $this->getUser();

        if($user){
            $basketEntity = $this->commandRepository->getBasketUser($user);

            if($basketEntity == null){
                $basketEntity = new Command();
                $basketEntity->setNumCommand(uniqid());
                $basketEntity->setStatus(100);
                $basketEntity->setCreatedAt( new DateTime());
                $basketEntity->setUser($user);
                $basketEntity->setTotalPrice(0);
                $this->em->persist($basketEntity);
                $this->em->flush();
            }

            return $basketEntity;
        }
    }

    // Fonction qui ajoute un produit au panier
    public function addProductToBasket(Product $product){

        $user = $this->getUser();

        if($user){
            $basketEntity = $this->getBasket($user);
            $basketEntity->addProduct($product);
            $this->em->persist($basketEntity);
            $this->em->flush();
        }

    }


    // Fonction qui retire un produit au panier
    public function removeProductToBasket(Product $product){

        $user = $this->getUser();

        if($user){
            $basketEntity = $this->getBasket($user);
            $basketEntity->removeProduct($product);
            $this->em->persist($basketEntity);
            $this->em->flush();
        }

    }

}



