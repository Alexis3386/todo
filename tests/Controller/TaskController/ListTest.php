<?php

namespace App\Tests\Controller\TaskController;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class ListTest extends WebTestCase
{

    public function testlistAction(): void
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/tasks');

        $numberOfTask = $crawler->filter('.thumbnail')->count();

        self::assertResponseStatusCodeSame(Response::HTTP_OK);

        self::assertGreaterThan(0, $numberOfTask);
    }

}