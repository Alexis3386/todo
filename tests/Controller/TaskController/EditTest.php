<?php

namespace App\Tests\Controller\TaskController;

use App\Tests\AbstractAppWebTestCase;
use Exception;

class EditTest extends AbstractAppWebTestCase
{
    /**
     * @throws Exception
     */
    public function testEdit(): void
    {
        $client = static::createClient();

        $tasks = $this->getEntityManager()->createQuery('SELECT t.id
            FROM App\Entity\Task t')
            ->getResult();

        $id = $tasks[0]['id'];

        $client->request('GET', '/tasks/' . $id . '/edit');

        $client->submitForm('Modifier', [
            'task[title]' => 'set by test',
            'task[content]' => 'content set by test',
        ]);

        $client->request('GET', '/tasks');
        self::assertSelectorTextContains('h4 a[href="' . '/tasks/' . $id . '/edit' . '"' . ']', 'set by test');
        self::assertSelectorTextContains('#task-' . $id, 'content set by test');
    }
}
