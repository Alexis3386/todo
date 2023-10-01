<?php

namespace App\Tests\Controller\TaskController;

use App\Entity\Task;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Tests\AbstractAppWebTestCase;
use Doctrine\Persistence\ObjectManager;
use Exception;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\HttpFoundation\Response;

class RemoveTest extends AbstractAppWebTestCase
{

    private KernelBrowser|null $client = null;
    private ObjectManager|null $entityManager = null;


    /**
     * @throws Exception
     */
    public function testDeleteTaskWithUserAdmin(): void
    {

        $client = $this->getLogedClient('Alexis');

        $crawler = $client->request('GET', '/tasks');

//        $tasks = $this->getEntityManager()->createQuery('SELECT t
//            FROM App\Entity\Task t
//            WHERE user
//            ')->getResult();
//
//        dd($tasks);

        $button = $crawler->filter('#delete-' . '20')->text();

//        dd($button);

        $this->client->click($button);

        $numberOfTasksAfterDelete = $this->numberOfTask($testUser);

        self::assertGreaterThan($numberOfTasksAfterDelete, $numberOfTasksBeforeDelete);

    }

    /**
     * @throws Exception
     */
    public function testDeleteTaskWithUserNotAdmin(): void
    {
        $crawler = static::createClient()->request('GET', '/login');

        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneBy(['username' => 'Alexy']);

        $this->client->loginUser($testUser);

        $numberOfTasksBeforeDelete = $this->numberOfTask($testUser);

        $crawler = $this->client->request('GET', '/tasks');

        self::assertResponseStatusCodeSame(Response::HTTP_OK);

        $button = $crawler->selectButton('Supprimer')->first()->form();

        $this->client->click($button);

        $numberOfTasksAfterDelete = $this->numberOfTask($testUser);

        self::assertGreaterThan($numberOfTasksAfterDelete, $numberOfTasksBeforeDelete);

    }


    private function numberOfTask(?User $user): int
    {

        if ($user !== null) {
            $tasks = $this->entityManager->getRepository(Task::class)->findBy(['user' => $user]);
            return count($tasks);
        }

        $tasks = $this->entityManager->getRepository(Task::class)->findAll();
        return count($tasks);
    }

}