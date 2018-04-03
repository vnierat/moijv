<?php

namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class ProductFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        for($i = 0 ; $i < 150; $i++) 
        {
            $product = new Product();
            $product->setTitle('Mon produit n°'.$i);
            $product->setDescription('description du produit_'.$i);
            $product->setImage("uploads/500x325.png");
            $manager->persist($product);
            
            $product->setOwner($this->getReference('user'. rand(0, 59)));
        }

        $manager->flush();
    }
    
    public function getDependencies(): array 
    {
        return 
        [
            UserFixtures::class
        ];
    }
}
