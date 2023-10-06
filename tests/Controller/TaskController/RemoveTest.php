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

        $client = $this->getLogedClient('Alex');

        $crawler = $client->request('GET', '/tasks');

        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneBy(['username' => 'Alex']);

        $numberOfTasksBeforeDelete = $this->numberOfTask($testUser);

        $tasks = $this->getEntityManager()->createQuery('SELECT t
            FROM App\Entity\Task t
            INNER JOIN t.user u
            WHERE t.user = :user')
            ->setParameter('user', $testUser)
            ->getResult();

        $id = $tasks[0]->getId();

        $button = $crawler->filter('#delete-' . $id)->link();

        $this->client->click($button);

        $numberOfTasksAfterDelete = $this->numberOfTask($testUser);

        self::assertGreaterThan($numberOfTasksAfterDelete, $numberOfTasksBeforeDelete);

    }

    /**
     * @throws Exception
     */
    public function testDeleteTaskOfAnotheUser(): void
    {
        $client = $this->getLogedClient('Alex');

        $crawler = $client->request('GET', '/tasks');

        $userRepository = static::getContainer()->get(UserRepository::class);
        $otherUser = $userRepository->findOneBy(['username' => 'Lucy']);

        $tasks = $this->getEntityManager()->createQuery('SELECT t
            FROM App\Entity\Task t
            INNER JOIN t.user u
            WHERE t.user = :user')
            ->setParameter('user', $otherUser)
            ->getResult();

        $id = $tasks[0]->getId();

        self::assertSelectorExists('#task-' . $id);
        self::assertSelectorNotExists('#delete-' . $id);
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