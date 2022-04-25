<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CategoryRepository;

class CategoryController extends AbstractController
{
    /**
     * @Route("/category", name="app_category")
     */
    public function index(): Response
    {
        return $this->render('category/index.html.twig', [
        ]);
    }

    /**
     * @Route("/category/{id}", name="app_product_category", requirements={"id" : "\d+"}, methods={"GET", "POST"})
     */
    public function category($id, CategoryRepository $categoryRepo): Response 
    {
            //on pourrait aussi utiliser find($id)

        $category = $categoryRepo->findOneBy(['id' => $id]);

        if(!$category)
        {
            throw new NotFoundHttpException("La catégorie $id n'éxiste pas");
        }

        return $this->render("category/category_product.html.twig", [
            'id' => $id,
            'category' => $category           
        ]);
    }
}