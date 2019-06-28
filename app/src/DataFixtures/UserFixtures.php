<?php
/**
 * User fixtures.
 */

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class UserFixtures.
 */
class UserFixtures extends AbstractBaseFixtures
{
    /**
     * Password encoder.
     *
     * @var \Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * UserFixtures constructor.
     *
     * @param \Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface $passwordEncoder Password encoder
     */
    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * Load.
     *
     * @param \Doctrine\Common\Persistence\ObjectManager $manager
     */
    public function loadData(ObjectManager $manager): void
    {
        $this->createMany(10, 'users', function ($i) {
            $user = new User();
            if (1 == $i) {
                $user->setName('user1');
            } else {
                $user->setName($this->faker->firstName);
            }
            $user->setRoles(['ROLE_USER']);
            $user->setPassword($this->passwordEncoder->encodePassword(
                $user,
                'user1234'
            ));

            return $user;
        });

//        $this->createMany(2, 'users', function ($i) {
//            $user = new User();
//            $names = ['user1', 'user2'];
//            $user->setName($names[$i]);
//            $user->setRoles(['ROLE_USER']);
//            $user->setPassword($this->passwordEncoder->encodePassword(
//                $user,
//                'user1234'
//            ));
//
//            return $user;
//        });

        $this->createMany(3, 'admins', function ($i) {
            $user = new User();
            $names = ['admin1', 'admin2', 'admin3'];
            $user->setName($names[$i]);
            $user->setRoles(['ROLE_ADMIN']);
            $user->setPassword($this->passwordEncoder->encodePassword(
                $user,
                'admin1234'
            ));

            return $user;
        });

        $manager->flush();
    }
}
