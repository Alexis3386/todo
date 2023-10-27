<?php

namespace App\Tests;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;


abstract class AbstractAppWebTestCase extends WebTestCase
{

    protected ?User $logedUser = null;

    protected function setUp(): void
    {
        parent::setUp();
        $this->logedUser = null;
    }

    /**
     * @throws Exception
     */
    protected function getLogedClient(string $username): KernelBrowser
    {
        $client = static::createClient();

        $userRepository = static::getContainer()->get(UserRepository::class);
        $this->logedUser = $userRepository->findOneBy(['username' => $username]);
        $client->loginUser($this->logedUser);

        return $client;
    }

    protected function getEntityManager(): EntityManagerInterface
    {
        $kernel = self::bootKernel();

        return $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

}