<?php

namespace App\Tests\Security\Voter;

use App\Entity\Task;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Security\Core\Authentication\Token\Storage\UsageTrackingTokenStorage;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\User\UserInterface;

class TaskVoterTest extends KernelTestCase
{


    public function setUp(): void
    {

    }

    public function testIsOwner(): void
    {
        $user = new User();
        $user->setRoles(['ROLE_ADMIN']);

        $this->initTokenStorage($user);

        $task = new Task();
        $task->setUser($user);

        $this->assertTrue($this->isGranted($task));
    }

    public function testIsNotOwner(): void
    {
        $user = new User();
        $user->setRoles(['ROLE_ADMIN']);

        $this->initTokenStorage($user);

        $task = new Task();
        $task->setUser(new User());

        $this->assertFalse($this->isGranted($task));
    }

    public function testIsAdminNotAffected(): void
    {
        $user = new User();
        $user->setRoles(['ROLE_ADMIN']);

        $this->initTokenStorage($user);

        $task = new Task();

        $this->assertTrue($this->isGranted($task));
    }

    public function testIsNotAdminNotAffected(): void
    {
        $user = new User();

        $this->initTokenStorage($user);

        $task = new Task();

        $this->assertFalse($this->isGranted($task));
    }

    public function testNotUserConnected(): void
    {
        $task = new Task();

        $this->assertFalse($this->isGranted($task));
    }

    private function isGranted(Task $task): bool
    {
        return self::getContainer()->get('security.authorization_checker')->isGranted('CAN_DELETE', $task);
    }

    private function initTokenStorage(UserInterface $user): void
    {
        /** @var UsageTrackingTokenStorage $tokenStorage */
        $tokenStorage = self::getContainer()->get('security.token_storage');
        $token = new UsernamePasswordToken($user, 'main', $user->getRoles());
        $tokenStorage->setToken($token);
    }

}
