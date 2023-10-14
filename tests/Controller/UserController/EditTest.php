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
    public function testCreateAction()
    {
        $client = $this->getLogedClient('Alex');

        /** @var User $user */
        $user = $this->getEntityManager()->getRepository(User::class)->findBy(['username' => 'Ryan']);

        $crawler = $client->request('GET', '/users/' . $user->getId() . 'create');

        $form = $crawler->selectButton('Modifier')->form();

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
}
