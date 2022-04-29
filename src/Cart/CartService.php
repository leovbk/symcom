<?php
namespace App\Cart;

use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;


class CartService 
{

  protected $session;
  protected $productRepo;

  public function __construct(SessionInterface $session, ProductRepository $productRepo)
  {
    $this->session = $session;
    $this->productRepo = $productRepo;
  }

  public function save($cart){
    $this->session->set('cart',$cart);
  }



  public function add(int $id)
  {
    $cart = $this->session->get('cart',[]);

    if(array_key_exists($id,$cart))
    {
      $cart[$id]++;
    } else {
      $cart[$id] = 1;
    }

    $this->save($cart);
  }

  public function remove(int $id)
  {
    $cart = $this->session->get('cart', []);

    unset($cart[$id]);

    $this->save($cart);
  }

  public function decrement(int $id)
  {
    $cart = $this->session->get('cart', []);

    if(!array_key_exists($id,$cart))
    {
      return;
    }

    if($cart[$id] === 1)
    {
      $this->remove($id);
      return;
    }
    else
    {
      $cart[$id]--;
      $this->save($cart);
    }
  }

  public function getTotal(): float
  {
    $total = 0;

    foreach($this->session->get('cart', []) as $id => $qte)
    {
      $product = $this->productRepo->find($id);

      if(!$product)
      {
        continue;
      }

      $total +=($product->getPrice() * $qte);
    }
    return $total;
  }

  public function getDetail() : array
  {
    $panier = [];

    foreach($this->session->get('cart',[]) as $id => $qte)
    {
      $product = $this->productRepo->find($id);

      if(!$product)
      {
        continue;
      }

      $panier[] = [
        'product' => $product,
        'qte' => $qte,
      ];
    }

    return $panier;
  }
}