<?php
/**
 * Category fixtures.
 */

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Generator;

/**
 * Class CategoryFixtures.
 */
class CategoryFixtures extends AbstractBaseFixtures
{
    /**
     * Faker.
     *
     * @var Generator
     */
    protected $faker;

    /**
     * Object manager.
     *
     * @var ObjectManager
     */
    protected $manager;

    /**
     * Load.
     *
     * @param ObjectManager $manager
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
}
