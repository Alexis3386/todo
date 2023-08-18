<?php

namespace App\Tests\Security\Voter;

use App\Security\Voter\TaskVoter;
use PHPUnit\Framework\TestCase;

class TaskVoterTest extends TestCase
{

    public function testSupported(): void
    {
        $this->assertTrue(true);
    }

    public function testSupported2(): void
    {
        $this->assertTrue(false);
    }
}
