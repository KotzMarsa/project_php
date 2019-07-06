<?php
/**
 * Meal fixtures.
 */

namespace App\DataFixtures;

use App\Entity\Meal;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Generator;

/**
 * Class MealFixtures.
 */
class MealFixtures extends AbstractBaseFixtures
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
     * Load data.
     *
     * @param ObjectManager $manager
     */
    public function loadData(ObjectManager $manager): void
    {
        $this->createMany(5, 'meals', function ($i) {
            $meals = array('sniadanie', 'drugie sniadanie', 'obiad', 'kolacja', 'inne');

            $meal = new Meal();
            $meal->setName($meals[$i]);

            return $meal;
        });

        $manager->flush();
    }
}
