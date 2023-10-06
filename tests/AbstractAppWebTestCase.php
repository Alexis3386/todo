<?php

namespace App\Tests;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;


abstract class AbstractAppWebTestCase extends WebTestCase
{
    /**
     * @throws Exception
     */
    protected function getLogedClient(string $username): KernelBrowser
    {
        $client = static::createClient();

        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneBy(['username' => $username]);
        $client->loginUser($testUser);

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