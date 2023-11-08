<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private Generator $faker;

    public function __construct(private readonly UserPasswordHasherInterface $userPasswordHasherInterface)
    {
        $this->faker = Factory::create('fr_FR');
        $this->faker->seed(157);
    }


    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setRoles(['ROLE_ADMIN']);
        $user->setPassword($this->userPasswordHasherInterface->hashPassword($user, 'password'));
        $user->setUsername('Alexis');
        $user->setEmail('test@exemple.com');
        $manager->persist($user);

        for ($i = 0; $i < 5; $i++) {
            $user = new User();
            $user->setRoles(['ROLE_USER']);
            $user->setPassword($this->userPasswordHasherInterface->hashPassword($user, 'password'));
            $user->setUsername($this->faker->name());
            $user->setEmail($this->faker->email());
            $manager->persist($user);
        }
        $manager->flush();
    }
}