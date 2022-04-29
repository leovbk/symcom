<?php

namespace App\Controller;

use App\Entity\Product;
use App\Cart\CartService;
use App\Form\PurchaseType;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CartController extends AbstractController
{
    /**
     * @Route("/cart/add/{id}", name="app_cart_add", requirements={"id" : "\d+"}, methods={"GET", "POST"})
     */
    public function add($id, ProductRepository $productRepo, CartService $cartService, Request $request) : Response
    {

        $product = $productRepo->find($id);

        if(!$product)
        {
            throw new NotFoundHttpException('Le produit' . $id . "n'existe pas en bdd");
        }

        $cartService->add($id);

        $this->addFlash('success',"Produit ajouté au panier");

        if($request->query->get('stay'))
        {
            return $this->redirectToRoute('app_cart');
        }

        return $this->redirectToRoute('app_show', [
                'id' => $id
            ]);
    }

    /**
     * @Route("/cart", name="app_cart")
     */

     public function showCart(CartService $cartService) : response
     {

        $form = $this->createForm(PurchaseType::class);

        $panier = $cartService->getDetail();

        $total = $cartService->getTotal();

        return $this->render('cart/index.html.twig',[
            'panier' => $panier,
            'total' => $total,
            'form' => $form->createView()
        ]);
     }

     /**
      * @Route("/cart/delete/{id}", name="cart_delete", requirements={"id" : "\d+"}, methods={"GET", "POST"})
      */

      public function deleteProduct($id, ProductRepository $productRepo, CartService $cartService) : Response 
      {
        
        $product = $productRepo->find($id);

        if(!$product)
        {
            throw $this->createNotFoundException("Cet article n'existe pas");
        }

        $cartService->remove($product->getId());

        $this->addFlash('success',"Le produit à bien été supprimé du panier");

        return $this->redirectToRoute('app_cart');
      }
      
      /**
       * @Route("/cart/remove-one/{id}", name="cart_remove_one", requirements={"id" : "\d+"}, methods={"GET", "POST"})
       */

       public function decrement($id, ProductRepository $productRepo, CartService $cartService, Request $request)
       {
            // $product = $productRepo->find($id);

        if(!$id)
        {
            throw $this->createNotFoundException("Cet article n'existe plus");
        }

        $cartService->decrement($id);

        if($request->query->get('stay'))
        {
            return $this->redirectToRoute('app_cart');
        }

        return $this->redirectToRoute('app_show', [
                'id' => $id
            ]);



       }
}