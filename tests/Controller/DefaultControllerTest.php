<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{

    /**
     * @return void
     */
    public function testHomePage(): void
    {
        $client = static::createClient();

        // Request a specific page
        $client->request('GET', '/');

        self::assertResponseIsSuccessful();

        self::assertSelectorTextContains('h1', "Bienvenue sur Todo List, l'application vous permettant de gérer l'ensemble de vos tâches sans effort !");
    }
}