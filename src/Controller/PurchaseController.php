<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Purchase;
use App\Cart\CartService;
use App\Form\PurchaseType;
use App\Entity\PurchaseDetail;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PurchaseController extends AbstractController
{
    /**
     * @Route("/purchase", name="app_purchase")
     */
    public function index(): Response
    {

        $user = $this->getUser();

        if(!$user)
        {
            $this->redirectToRoute('app_login');
        }
        return $this->render('purchase/index.html.twig', [
            'controller_name' => 'PurchaseController',
        ]);
    }

    /**
     * @Route("/purchase/confirm", name="purchase_confirm")
     */

     public function purchaseConfirm(Request $request, CartService $cartService, EntityManagerInterface $manager): Response
     {
         //0 si la personne n'est pas connecté, rediriger
         
            if(!$this->getUser())
            {
                $this->addFlash('warning', "Vous n'êtes pas authentifié, connectez vous pour confirmer votre commande");
                return $this->redirectToRoute('app_login');
            }
            
            //1) Un formulaire de commande (make:form)

            $form = $this->createForm(PurchaseType::class);

            //2) Lire le formulaire et le remplir avec les information de la commande (form->handlerequest)

            $form->handleRequest($request);



            //plan B a cause des Asset qui ne fonctionen pas 

            if($form->get('fullName')->getData() == null || $form->get('address')->getData() == null || $form->get('postalCode')->getData() == null || $form->get('city')->getData() == null)
            {
                
                $this->addFlash('warning', "Merci de remplir tous les champs afin de finaliser votre commande");
                return $this->redirectToRoute('cart_show');
            }

            //3) fomulaire valide? soumis ou non? (isSubmited && isValid())

            if(!$form->isSubmitted())
            {
                $this->addFlash('warning', "Vous devez remplir le formulaire pour valider la commande");

                return $this->redirectToRoute('app_cart');

            }

            //4)S'il n'y a rien dans le panier, on redirige (SessionInterface / CartService)

            $cartItems = $cartService->getDetail();

            if(count($cartItems) == 0)
            {
                $this->addFlash('warning', "Vous devez remplir le formulaire pour valider la commande");

                return $this->redirectToRoute('app_cart');
            }


            //5)Créer la commande (instance de Purchase $purchase)

            /**@var Purchase  */
            $purchase = $form->getData();

            // dump($purchase);

            //6) Relier la commande avec l'utilisateur en cours

            $purchase->setUser($this->getUser())
                     ->setPurchaseAt(new \Datetime)
                     ->setTotal($cartService->getTotal());

            $manager->persist($purchase);

            //7) Lier le panier et la commande (Session ou CartService), instance de purchasedetail

            foreach($cartService->getDetail() as $item)
            {
                $purchaseDetail = new PurchaseDetail;

                $purchaseDetail->setPurchase($purchase)
                               ->setProduct($item->product)
                               ->setProductName($item->product->getTitle())
                               ->setQuantity($item->qte)
                               ->setTotal($item->getTotal())
                               ->setProductPrice($item->product->getPrice());

                               $manager->persist($purchaseDetail);
            }

            //8)Enregistré la commande en bdd (EntityManangerInterface)

            $manager->flush();

            $this->addFlash('success', "La commande a bien été enregistrée");
            
         return $this->render('purchase/confirm.html.twig');
     }

     /**
     * @Route("/purchase/orders", name="purchase_order")
     */
    public function order(): Response
    {
        /** @var User */
        $user = $this->getUser();

        if(!$user)
        {
            $this->redirectToRoute('app_login');
        }
        return $this->render('purchase/order.html.twig', [
            "commande" => $user->getPurchases()
        ]);
    }
}