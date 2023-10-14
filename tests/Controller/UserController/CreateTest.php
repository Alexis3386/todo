<?php

namespace App\Tests\Controller\UserController;

use App\Entity\User;
use App\Tests\AbstractAppWebTestCase;
use Exception;

class CreateTest extends AbstractAppWebTestCase
{

    /**
     * @throws Exception
     */
    public function testCreateAction(): void
    {

        $client = $this->getLogedClient('Alex');

        $crawler = $client->request('GET', '/users/create');

        $form = $crawler->selectButton('Ajouter')->form();

        $form->setValues(
            [
                'user[username]' => 'Ryan',
                'user[password][first]' => 'password',
                'user[password][second]' => 'password',
                'user[email]' => 'test@exemple.com',
            ]);

        $form['user[roles]']->select('ROLE_USER');

        $client->submit($form);

        $client->followRedirect();

        $user = $this->getEntityManager()->getRepository(User::class)->findBy(['username' => 'Ryan']);

        self::assertNotNull($user);

    }

    /**
     * @throws Exception
     */
    public function testCreateActionWithEmailAllreadyUsed(): void
    {

        $client = $this->getLogedClient('Alex');

        $crawler = $client->request('GET', '/users/create');

        $form = $crawler->selectButton('Ajouter')->form();

        $form->setValues(
            [
                'user[username]' => 'Ryan',
                'user[password][first]' => 'password',
                'user[password][second]' => 'password',
                'user[email]' => 'test@exemple.com',
            ]);

        $form['user[roles]']->select('ROLE_USER');

        $client->submit($form);

        $user = $this->getEntityManager()->getRepository(User::class)->findBy(['email' => 'test@exemple.com']);

        self::assertNotNull($user);
        self::assertSelectorTextContains('.invalid-feedback.d-block', 'This value is already used.');
    }

    public function testCreateActionWithbadRepeatPassword(): void
    {

        $client = $this->getLogedClient('Alex');

        $crawler = $client->request('GET', '/users/create');

        $form = $crawler->selectButton('Ajouter')->form();

        $form->setValues(
            [
                'user[username]' => 'Ryan',
                'user[password][first]' => 'password',
                'user[password][second]' => 'password2',
                'user[email]' => 'test@exemple.com',
            ]);

        $form['user[roles]']->select('ROLE_USER');

        $client->submit($form);

        $user = $this->getEntityManager()->getRepository(User::class)->findBy(['email' => 'test@exemple.com']);

        self::assertNotNull($user);
        self::assertSelectorTextContains('.invalid-feedback.d-block',
            'Les deux mots de passe doivent correspondre.');
    }
}
