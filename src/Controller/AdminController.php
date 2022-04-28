<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="app_admin")
     */
    public function index(): Response
    {
        return $this->render('admin/index.html.twig', [
        ]);
    }

    /**
     * @Route("/admin/products", name="app_admin_products")
     */

    public function adminProducts(ProductRepository $productRepository): Response
    {

        $em = $this->getDoctrine()->getManager();

        $colonnes = $em->getClassMetadata(Product::class)->getFieldNames();

        $products = $productRepository->findAll();

        return $this->render('admin/products.html.twig', [
            'colonnes' => $colonnes,
            'products' => $products
        ]);
    }

    /**
     * @Route("/admin/products/create", name="app_admin_products_create")
     * @Route("/admin/products/edit/{id}", name="app_admin_products_edit")
     */

     public function adminProductCreate(Product $product = null, Request $request, EntityManagerInterface $manager, SluggerInterface $slugger) : Response 
     {

        if(!$product){
           $product = new Product();
        }

        $id=$product->getId();

        

        $form = $this->createForm(ProductType::class, $product);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            //class symfo qui permet de traiter les fichiers

            /** @var UploadedFile $imageFile */


            //on recupere toutes les datas de l'image

            $imageFile = $form->get('picture')->getData();


            //on récupere le nom de l'image

            if($imageFile){
                $originalName = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);

                $sluggedFileName = $slugger->slug($originalName);

                $newImageName = $sluggedFileName. '-' . uniqid() .'.'. $imageFile->guessExtension();

                //on va tenter de copier l'image dans le bon dossier

                try
                {
                    $imageFile->move($this->getParameter('image_directory'), $newImageName);
                } 
                catch (FileException $e) 
                {
                    echo "Erreur: ". $e->getMessage();
                }

                $product->setPicture($newImageName);
            }

            $manager->persist($product);
            $manager->flush();

            $this->addFlash('success', "Le produit: " . $product->getTitle() . " a bien été ajouté");

            return $this->redirectToRoute('app_admin_products');
        }

        return $this->render('admin/products/create.html.twig', [
            'form' => $form->createView(),
            'id' => $id,
            'product' => $product,
        ]);
     }

     /**
      * @Route("/admin/products/delete/{id}", name="app_admin_products_delete")
      */

      public function adminDeleteProduct(Product $product, EntityManagerInterface $manager) : Response 
      {
        
        $manager->remove($product);

        $manager->flush();

        $this->addFlash('danger', "Le produit: " . $product->getTitle() . " a bien été supprimé");

        return $this->redirectToRoute('app_admin_products');
      }
}