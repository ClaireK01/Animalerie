<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\ProductPicture;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use App\Repository\ReviewRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class ProductController extends AbstractController
{
    public function __construct(
        private ProductRepository $productRepository,
        private EntityManagerInterface $em,
        private ReviewRepository $reviewRepository,
    ){}

    #[Route('/product_add', name: 'app_product_add')]
    public function add(Request $request, SluggerInterface $slugger): Response
    {   
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            //Recupere l'image
            $imageFile = $form->get('image')->getData();
            //Si il y a une image
            if ($imageFile) {
                //Recupere le nom original de la photo
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $imageFile->move(
                        $this->getParameter('images_product_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }
                // J'instancie une entité PictureProducts pour faire le lien entre l'image et le produit
                $entity = new ProductPicture();
                $entity->setPath($newFilename);
                $entity->setLibelle($originalFilename);
                $product->addProductPicture($entity);

                // updates the 'imageFilename' property to store the PDF file name
                // instead of its contents
            }

            $this->em->persist($product);
            $this->em->flush();
            return $this->redirectToRoute('app_animalerie_home');
        }

        return $this->render('product/product_add.html.twig', [
            'form'=>$form->createView(),
        ]);
    }

    #[Route('/product/{id}', name:'app_product_show')]
    public function show($id){
        $product = $this->productRepository->find($id);
        $reviews = $this->reviewRepository->getReviewsByProducts($product);
        $sameBrandProduct = $this->productRepository->getSameBrandProducts($product->getBrand());
        $similarProducts = $this->productRepository->getSimilarProducts($product->getLabel(), $product->getCategories());

        // ##### Calucul de moyenne ########
        $sommeNotes = 0;
        $totalNotes = 0;
        foreach($reviews as $review){
            $totalNotes++;
            $sommeNotes = $sommeNotes + $review->getNote();
        }
        $averageNote = round($sommeNotes / $totalNotes, 1);
        // #############


        return $this->render('product/product_show.html.twig', [
            'product'=>$product,
            'reviews'=>$reviews,
            'averageNote'=>$averageNote,
            'sameBrandProducts'=>$sameBrandProduct,
            'similarProducts'=>$similarProducts
        ]);
    }
}
