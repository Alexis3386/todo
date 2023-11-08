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

    /**
     * @throws Exception
     */
    public function testDeleteTaskWithUserAdmin(): void
    {

        $client = $this->getLogedClient('Alex');

        $client->request('GET', '/tasks');

        $numberOfTasksBeforeDelete = $this->numberOfTask($this->logedUser);

        $tasks = $this->getEntityManager()->createQuery('SELECT t
            FROM App\Entity\Task t
            INNER JOIN t.user u
            WHERE t.user = :user
            ORDER BY t.createdAt DESC')
            ->setParameter('user', $this->logedUser)
            ->setMaxResults(1)
            ->getSingleResult();

        $client->submitForm('delete-' . $tasks->getId());

        $numberOfTasksAfterDelete = $this->numberOfTask($this->logedUser);

        self::assertGreaterThan($numberOfTasksAfterDelete, $numberOfTasksBeforeDelete);

    }

    /**
     * @throws Exception
     */
    public function testDeleteTaskOfAnotheUser(): void
    {
        $client = $this->getLogedClient('Alex');

        $client->request('GET', '/tasks');

        $userRepository = static::getContainer()->get(UserRepository::class);
        $otherUser = $userRepository->findOneBy(['username' => 'Luc']);

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
    public function testDeleteTaskOwnedWithUserNotAdmin(): void
    {
        $client = $this->getLogedClient('Luc');

        $userRepository = static::getContainer()->get(UserRepository::class);
        $user = $userRepository->findOneBy(['username' => 'Luc']);

        $crawler = $client->request('GET', '/tasks');

        $tasks = $this->getEntityManager()->createQuery('SELECT t
            FROM App\Entity\Task t
            INNER JOIN t.user u
            WHERE t.user = :user')
            ->setParameter('user', $user)
            ->getResult();

        $id = $tasks[0]->getId();

        self::assertSelectorExists('#task-' . $id);
        self::assertSelectorExists('#delete-' . $id);

        $numberTasksBeforDelete = $this->numberOfTask($user);
        $crawler->filter('#delete-' . $id)->selectButton('Supprimer');
        $client->submitForm('Supprimer');

        $numberTasksAfterDelete = $this->numberOfTask($user);
        self::assertGreaterThan($numberTasksAfterDelete, $numberTasksBeforDelete);
    }

    /**
     * @throws Exception
     */
    public function testDelete(): void
    {
        $crawler = $this->getLogedClient('Luc');

        $userRepository = static::getContainer()->get(UserRepository::class);
        $user = $userRepository->findOneBy(['username' => 'Luc']);

        $crawler->request('GET', '/tasks');

        $tasks = $this->getEntityManager()->createQuery('SELECT t
            FROM App\Entity\Task t
            INNER JOIN t.user u
            WHERE t.user = :user')
            ->setParameter('user', $user)
            ->getResult();

        $id = $tasks[0]->getId();

        self::assertSelectorExists('#task-' . $id);
        self::assertSelectorExists('#delete-' . $id);
    }


    private function numberOfTask(?User $user): int
    {

        if ($user !== null) {
            $tasks = $this->getEntityManager()->getRepository(Task::class)->findBy(['user' => $user]);
            return count($tasks);
        }

        $tasks = $this->getEntityManager()->getRepository(Task::class)->findAll();
        return count($tasks);
    }

}