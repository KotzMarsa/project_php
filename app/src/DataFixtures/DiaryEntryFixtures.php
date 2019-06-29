<?php

namespace App\DataFixtures;

use App\Entity\DiaryEntry;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class DiaryEntryFixtures extends AbstractBaseFixtures implements DependentFixtureInterface
{
    /**
     * Load data.
     *
     * @param \Doctrine\Common\Persistence\ObjectManager $manager Object manager
     */
    public function loadData(ObjectManager $manager): void
    {
        $this->createMany(200, 'diary_entries', function ($i) {
            $entry = new DiaryEntry();
            $entry->setDate($this->faker->dateTimeBetween('-5 days', '+1 days'));
            $entry->setServing($this->faker->numberBetween(0, 1000));
            $entry->setProduct($this->getRandomReference('products'));
            $entry->setMeal($this->getRandomReference('meals'));
            $entry->setUser($this->getRandomReference('users'));

            return $entry;
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
        return [ProductFixtures::class, MealFixtures::class, UserFixtures::class];
    }
}
