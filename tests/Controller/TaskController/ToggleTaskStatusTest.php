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
        $form = $crawler->filter('.btn.btn-success.btn-sm.pull-right')->form();
        $buttonTextInit = $crawler->filter('.btn.btn-success.btn-sm.pull-right')->text();

        $this->client->submit($form);

        $crawler = $this->client->request('GET', '/tasks');
        $buttonText2 = $crawler->filter('.btn.btn-success.btn-sm.pull-right')->text();
        $form = $crawler->filter('.btn.btn-success.btn-sm.pull-right')->form();

        $this->client->submit($form);

        $crawler = $this->client->request('GET', '/tasks');
        $buttonText3 = $crawler->filter('.btn.btn-success.btn-sm.pull-right')->text();

        self::assertNotSame($buttonTextInit, $buttonText2);
        self::assertSame($buttonTextInit, $buttonText3);
    }

}