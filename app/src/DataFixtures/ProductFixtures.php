<?php

namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class ProductFixtures.
 */
class ProductFixtures extends AbstractBaseFixtures
{
    /**
     * Load.
     *
     * @param \Doctrine\Common\Persistence\ObjectManager $manager
     */
    public function loadData(ObjectManager $manager): void
    {
        $this->createMany(30, 'products', function ($i) {
            $product = new Product();
            $product->setProductName($this->faker->sentence);
            $product->setCalories($this->faker->numberBetween(0, 100));
            $product->setCarbohydrate($this->faker->numberBetween(0, 50));
            $product->setFat($this->faker->numberBetween(0, 50));
            $product->setProtein($this->faker->numberBetween(0, 100 - $product->getFat() - $product->getCarbohydrate()));
            $product->setIsAccepted($this->faker->boolean());
            $product->setCategory($this->getRandomReference('categories'));

            //$this->manager->persist($product);
            return $product;
        });

        $manager->flush();
    }
}
