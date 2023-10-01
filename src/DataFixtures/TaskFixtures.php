<?php

namespace App\DataFixtures;

use App\Entity\Task;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

class TaskFixtures extends Fixture implements DependentFixtureInterface
{

    private Generator $faker;

    public function __construct(private readonly UserRepository $userRepository)
    {
        $this->faker = Factory::create('fr_FR');
        $this->faker->seed(157);
    }


    public function load(ObjectManager $manager): void
    {
        // anonymous task
        $task = new Task();
        $task->setTitle($this->faker->sentence(3));
        $task->setContent($this->faker->text(50));
        $task->setUser(null);
        $task->setCreatedAt(new \DateTime());
        $manager->persist($task);
        $manager->flush();

        $users = $this->userRepository->findAll();


        foreach ($users as $user) {
            for ($i = 0; $i < 3; $i++) {
                $task = new Task();
                $task->setTitle($this->faker->sentence(3));
                $task->setContent($this->faker->text(50));
                $task->setCreatedAt(new \DateTime());
                $task->setUser($user);
                $manager->persist($task);
            }
        }

        $manager->flush();

    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
        ];
    }
}