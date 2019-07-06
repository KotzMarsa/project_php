<?php
/**
 * Product fixtures.
 */
namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class ProductFixtures.
 */
class ProductFixtures extends AbstractBaseFixtures implements DependentFixtureInterface
{
    /**
     * Load.
     *
     * @param ObjectManager $manager
     */
    public function loadData(ObjectManager $manager): void
    {
        $this->createMany(100, 'products', function ($i) {
            $product = new Product();
            $product->setProductName($this->faker->sentence(3, true));
            $product->setCalories($this->faker->numberBetween(0, 1000));
            $product->setCarbohydrate($this->faker->numberBetween(0, 60));
            $product->setFat($this->faker->numberBetween(0, 40));
            $product->setProtein($this->faker->numberBetween(0, 100 - $product->getFat() - $product->getCarbohydrate()));
            $product->setIsAccepted($this->faker->boolean());
            $product->setCategory($this->getRandomReference('categories'));
            $product->setUser($this->getRandomReference('users'));
            //$this->manager->persist($product);
            return $product;
        });

        $manager->flush();
    }

    /**
     * This method must return an array of fixtures classes
     * on which the implementing class depends on.
     *
     * @return array Array of dependencies
     */
    public function getDependencies(): array
    {
        return [CategoryFixtures::class, UserFixtures::class];
    }
}
