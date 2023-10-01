<?php

namespace App\Tests\Controller\SecurityController;

use App\Repository\UserRepository;
use App\Tests\AbstractAppWebTestCase;
use Exception;
use Symfony\Component\HttpFoundation\Response;

class LoginTest extends AbstractAppWebTestCase
{

    /**
     * test connection
     * @throws Exception
     */
    public function testLogin(): void
    {

        $client = static::createClient();

        $crawler = $client->request('GET', '/login');

        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneBy(['username' => 'Alexis']);

//        $client->loginUser($testUser);

        $crawler = $client->request('GET', '/');

        self::assertResponseStatusCodeSame(Response::HTTP_OK);
        self::assertSelectorExists('a[href="/logout"]');
        self::assertSelectorNotExists('a[href="/login"]');

    }

    /**
     * @return void
     * @throws Exception
     */
    public function testLogout(): void
    {

        $client = $this->getLogedClient('Alexis');

        $crawler = $client->request('GET', '/');

        $link = $crawler->filter('a[href="/logout"]')->link();
        $client->click($link);
        $client->followRedirect();

        self::assertSelectorExists('a[href="/login"]');

    }

}
