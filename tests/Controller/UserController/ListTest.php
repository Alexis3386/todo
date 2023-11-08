<?php

namespace App\Tests\Controller\UserController;

use App\Tests\AbstractAppWebTestCase;
use Symfony\Component\HttpFoundation\Response;

class ListTest extends AbstractAppWebTestCase
{

    public function testlistAction(): void
    {

        $client = $this->getLogedClient('Alex');

        $crawler = $client->request('GET', '/users');

        $numberOfUser = $crawler->filter('tr')->count();

        self::assertResponseStatusCodeSame(Response::HTTP_OK);

        self::assertGreaterThan(0, $numberOfUser);
    }

}