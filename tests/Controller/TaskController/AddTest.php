<?php

namespace App\Tests\Controller\TaskController;

use App\Entity\Task;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Tests\AbstractAppWebTestCase;
use Exception;
use Symfony\Component\HttpFoundation\Response;

class AddTest extends AbstractAppWebTestCase
{
    public function testAddTaskWithoutUser(): void
    {

        $client = static::createClient();

        $crawler = $client->request('GET', '/tasks/create');

        self::assertResponseRedirects('http://localhost/login');

    }

    /**
     * @throws Exception
     */
    public function testAddTaskWithUser(): void
    {

        $client = static::createClient();
        $crawler = $client->request('GET', '/login');

        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneBy(['username' => 'Alexis']);

        $client->loginUser($testUser);

        $numberofTAskBeforeAdd = $this->numberOfTask($testUser);

        $crawler = $client->request('GET', '/tasks/create');

        self::assertResponseStatusCodeSame(Response::HTTP_OK);

        $client->submitForm('Ajouter', [
            'task[title]' => 'new Task',
            'task[content]' => 'test'
        ]);

        $numberofTAskAfterAdd = $this->numberOfTask($testUser);

        self::assertGreaterThan($numberofTAskBeforeAdd, $numberofTAskAfterAdd);


    }

    private function numberOfTask(?User $user): int
    {
        $tasks = 0;

        $em = $this->getEntityManager();

        if ($user !== null) {
            $tasks = $em->getRepository(Task::class)->findBy(['user' => $user]);
            return count($tasks);
        }

        $tasks = $em->getRepository(Task::class)->findAll();
        return count($tasks);
    }

}