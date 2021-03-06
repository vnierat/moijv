<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/product")
 */

class ProductController extends Controller
{
    /**
     * @Route("/", name="product")
     * @Route("/{page}", name="product_paginated", requirements={"page":"\d+"})
     */
    public function index(ProductRepository $productRepo, $page = 1)
    {
        $productList = $productRepo->findPaginatedByUser($this->getUser(), $page);
        
        return $this->render('product/index.html.twig', [
            'products' => $productList
        ]);
    }
    
    /**
     * @Route("/delete/{id}", name="delete_product")
     */
    public function deleteProduct(Product $product, ObjectManager $manager)
    {
    if($product->getOwner()->getId() !== $this->getUser()->getId())
        {
        throw $this->createAccessDeniedException('You Shall not pass !');
        }
    
        
        $manager->remove($product);
        $manager->flush();
        return $this->redirectToRoute('product');
    }
    
    /**
     * @Route("/add", name="add_product")
     * @Route("/edit/{id}", name="edit_product")
     */
    public function editProduct(Request $request, ObjectManager $manager, Product $product = null)
    {
        if($product === null)
        {
            $product = new Product();
            $group = 'insertion';
        }
        else
        {
           $oldImage = $product->getImage();
            $product->setImage(new File($product->getImage()));
            $group = 'edition';
        }
        
        $formProduct = $this->createForm(ProductType::class, $product , ['validation_groups'=>[$group]])
            ->add('Envoyer', SubmitType::class);
        
        $formProduct->handleRequest($request);
        
        // TODO insert entity after validation
        if($formProduct->isSubmitted() && $formProduct->isValid())
        {
            $product->setOwner($this->getUser());
            $image = $product->getImage();
            if($image === null)
            {
                $product->setImage($oldImage);
            }
            else 
            {
            $newFileName = md5(uniqid()) . '.' . $image->guessExtension();
            $image->move('uploads', $newFileName);
            $product->setImage('uploads/'.$newFileName);
            }
            $manager->persist($product);
            $manager->flush();
            return $this->redirectToRoute('product');
        }
        return $this->render('product/edit_product.html.twig', [
            'form'=> $formProduct->createView()
        ]);
    }
}
