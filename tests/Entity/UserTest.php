<?php

namespace App\Tests\Domain\Entity;

use App\Entity\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testUserEmailCanBeSetAndRetrieved(): void
    {
        $user = new User();
        $user->setEmail('john@example.com');

        $this->assertSame('john@example.com', $user->getEmail());
    }

    public function testUserPasswordCanBeSetAndRetrieved(): void
    {
        $user = new User();
        $user->setPassword('secure-password');

        $this->assertSame('secure-password', $user->getPassword());
    }
}
