<?php

namespace App\Tests\Controller\UserController;

use App\Entity\User;
use App\Tests\AbstractAppWebTestCase;
use Exception;

class EditTest extends AbstractAppWebTestCase
{
    /**
     * @throws Exception
     */
    public function testEditEmailAction(): void
    {
        $client = $this->getLogedClient('Alex');

        /** @var User $user */
        $user = $this->getEntityManager()->getRepository(User::class)->findOneBy(['username' => 'Luc']);
        $emailBeforeEdit = $user->getEmail();

        $crawler = $client->request('GET', '/users/' . $user->getId() . '/edit');

        $form = $crawler->selectButton('Modifier')->form();
        $form->setValues(
            [
                'user[username]' => 'Luc',
                'user[password][first]' => 'password',
                'user[password][second]' => 'password',
                'user[email]' => 'sophie@test23.com',
            ]);
        $form['user[roles]']->select('ROLE_USER');

        $client->submit($form);
        $client->followRedirect();
        $crawler = $client->request('GET', '/users');

        $emailAfterEdit = $crawler->filter('tr#' . $user->getId() . ' .email')->text();

        self::assertNotSame($emailAfterEdit, $emailBeforeEdit);

    }

    /**
     * @throws Exception
     */
    public function testEditName(): void
    {
        $client = $this->getLogedClient('Alex');

        /** @var User $user */
        $user = $this->getEntityManager()->getRepository(User::class)->findOneBy(['username' => 'Marcel']);
        $nameBeforeEdit = $user->getUsername();

        $crawler = $client->request('GET', '/users/' . $user->getId() . '/edit');

        $form = $crawler->selectButton('Modifier')->form();
        $form->setValues(
            [
                'user[username]' => 'MarcelTest',
                'user[password][first]' => 'password',
                'user[password][second]' => 'password',
                'user[email]' => 'stephanie71@tele2.fr',
            ]);
        $form['user[roles]']->select('ROLE_USER');

        $client->submit($form);
        $client->followRedirect();
        $crawler = $client->request('GET', '/users');

        $nameAfterEdit = $crawler->filter('tr#' . $user->getId() . ' .name')->text();

        self::assertNotSame($nameBeforeEdit, $nameAfterEdit);

    }

}
