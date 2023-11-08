<?php

namespace App\Tests\Controller\TaskController;

use App\Entity\Task;
use App\Tests\AbstractAppWebTestCase;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class ToggleTaskStatusTest extends AbstractAppWebTestCase
{

    /**
     * @throws \Exception
     */
    public function testToggletaskStatus(): void
    {
        $client = $this->getLogedClient('Alex');
        $crawler = $client->request('GET', '/tasks');
        $form = $crawler->filter('.btn.btn-success.btn-sm.pull-right')->form();
        $buttonTextInit = $crawler->filter('.btn.btn-success.btn-sm.pull-right')->text();

        $client->submit($form);

        $crawler = $client->request('GET', '/tasks');
        $buttonText2 = $crawler->filter('.btn.btn-success.btn-sm.pull-right')->text();
        $form = $crawler->filter('.btn.btn-success.btn-sm.pull-right')->form();

        $client->submit($form);

        $crawler = $client->request('GET', '/tasks');
        $buttonText3 = $crawler->filter('.btn.btn-success.btn-sm.pull-right')->text();

        self::assertNotSame($buttonTextInit, $buttonText2);
        self::assertSame($buttonTextInit, $buttonText3);
    }

}