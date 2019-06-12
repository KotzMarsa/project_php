<?php
/**
 * Category fixtures.
 */

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class CategoryFixtures.
 */
class CategoryFixtures extends AbstractBaseFixtures
{
    /**
     * Faker.
     *
     * @var \Faker\Generator
     */
    protected $faker;

    /**
     * Object manager.
     *
     * @var \Doctrine\Common\Persistence\ObjectManager
     */
    protected $manager;

    /**
     * Load.
     *
     * @param \Doctrine\Common\Persistence\ObjectManager $manager
     */
    public function loadData(ObjectManager $manager): void
    {
        $this->createMany(15, 'categories', function ($i) {
            $categories = ['warzywa', 'owoce', 'pieczywo', 'kasze', 'ryże',
            'produkty zbożowe', 'orzechy', 'gotowe dania', 'zupy', 'dania',
            'napoje zimne', 'napoje gorace', 'slodycze', 'słone przekąski', 'oleje', ];

            $category = new Category();
            $category->setName($categories[$i]);

            return $category;
        });

        $manager->flush();
    }

//    /**
//     * Load data.
//     *
//     * @param \Doctrine\Common\Persistence\ObjectManager $manager
//     */
//    protected function loadData(ObjectManager $manager): void
//    {
//        // TODO: Implement loadData() method.
//    }
}
