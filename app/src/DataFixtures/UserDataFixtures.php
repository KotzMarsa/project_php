<?php


namespace App\DataFixtures;

use App\Entity\UserData;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
/**
 * Class UserDataFixtures
 * @package App\DataFixtures
 */
class UserDataFixtures extends AbstractBaseFixtures implements DependentFixtureInterface
{
    /**
     * Load data.
     *
     * @param \Doctrine\Common\Persistence\ObjectManager $manager Object manager
     */
    public function loadData(ObjectManager $manager): void
    {
        $this->createMany(200, 'user_datas', function ($i) {
            $userData = new UserData();
            $userData->setCalorieGoal($this->faker->numberBetween(1000, 6000));
            $userData->setWeight($this->faker->numberBetween(40, 120));
            $userData->setHeight($this->faker->numberBetween(150, 200));
            $userData->setDate($this->faker->dateTimeBetween('-10 days', '+0 days'));
            $userData->setUser($this->getRandomReference('users'));

            return $userData;
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
        return [UserFixtures::class];
    }
}
