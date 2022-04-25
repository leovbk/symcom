<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Product;
use Symfony\Component\String\Slugger\SluggerInterface;
use Faker\Factory;
use App\Entity\Category;

class ProductFixtures extends Fixture
{
    protected $slugger;

        public function __construct(SluggerInterface $slugger){
            $this->slugger = $slugger;
        }
    public function load(ObjectManager $manager): void  
    {   

        $faker = \Faker\Factory::create('fr_FR');

        for($j = 0; $j <= 4; $j++){

            $category = new Category;

            $category->setName($faker->sentence())
                     ->setSlug($this->slugger->slug(strtoupper($category->getName())));

            $manager->persist($category);    
            
            // A chaque tour de boucle on créé une instance de l'entity Product
            for($i = 0; $i < 12; $i++){

                $product = new Product();

                $product->setTitle($faker->sentence())
                        ->setContent($faker->realText($faker->numberBetween(10, 20)))
                        ->setPrice(mt_rand(15, 35))
                        ->setPicture("https://picsum.photos/id/" . mt_rand(10, 100) . "/800/500")
                        ->setCategory($category);

                // Persist envoi les valeurs de l'instance en cache

            $manager->persist($product);
            }
    }
        //Flush envoi tout en bdd
        $manager->flush();
    }
}