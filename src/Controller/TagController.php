<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Zend\Code\Generator\DocBlock\Tag;

/**
 * @Route("/tag")
 */

class TagController extends Controller
{
    /**
     * @Route("/{slug}/product", name="tag")
     */
    public function product(Tag $tag)
    {
        return $this->render('tag/product.html.twig', [
            'controller_name' => 'TagController',
        ]);
    }
}
