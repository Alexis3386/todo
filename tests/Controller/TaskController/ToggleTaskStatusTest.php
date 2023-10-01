<?php

namespace App\Tests\Controller\TaskController;

use App\Entity\Task;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class ToggleTaskStatusTest extends WebTestCase
{

    private KernelBrowser|null $client = null;
    private ObjectManager|null $entityManager = null;

    public function setUp(): void

    {
        $this->client = static::createClient();
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    /**
     * @throws \Exception
     */
    public function testToggletaskStatus(): void
    {

        $crawler = $this->client->request('GET', '/tasks');

        self::assertResponseStatusCodeSame(Response::HTTP_OK);

        $form = $crawler->filter('.btn.btn-success.btn-sm.pull-right')->form();
        $taskFormUri = $form->getUri();


        $taskId = (int)explode('/', $taskFormUri)[4];

        $task = $this->entityManager->getRepository(Task::class)->findOneBy(['id' => $taskId]);

        $statusBeforeToggle = $task->isDone();

        $crawler = $this->client->submit($form);
        $this->client->followRedirect();

        $statusAfterToggle = $task->isDone();

        self::assertNotSame($statusBeforeToggle, $statusAfterToggle);

    }

}