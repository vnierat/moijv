<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends Controller
{
    /**
     * @Route("/product", name="product")
     * @Route("/product/{page}", name="product_paginated")
     */
    public function index(ProductRepository $productRepo, $page = 1)
    {
        $productList = $productRepo->findPaginated($page);
        
        return $this->render('product/index.html.twig', [
            'products' => $productList
        ]);
    }
    
    /**
     * @Route("/product/delete/{id}", name="delete_product")
     */
    
    public function deleteProduct(Product $product, ObjectManager $manager)
    {
        $manager->remove($product);
        $manager->flush();
        return $this->redirectToRoute('product');
    }
    
    /**
     * @Route("/product/add", name="add_product")
     * @Route("/product/edit/{id}", name="edit_product")
     */
    public function editProduct(Request $request, ObjectManager $manager, Product $product = null)
    {
        if($product === null)
        {
            $product = new Product();
        }
        
        $formProduct = $this->createForm(ProductType::class, $product)
            ->add('Envoyer', SubmitType::class);
        
        $formProduct->handleRequest($request);
        
        // TODO insert entity after validation
        if($formProduct->isSubmitted() && $formProduct->isValid())
        {
            $manager->persist($product);
            $manager->flush();
            return $this->redirectToRoute('product');
        }
        return $this->render('product/edit_product.html.twig', [
            'form'=> $formProduct->createView()
        ]);
    }
}
