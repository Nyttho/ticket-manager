<?php

namespace App\Tests\Domain;

use PHPUnit\Framework\TestCase;
use App\Domain\Ticket;

class TicketTest extends TestCase
{
    public function testStartChangesStateToInProgress(): void
    {
        $ticket = new Ticket('Titre', 'Description', 'normale');

        $this->assertSame('PENDING', $ticket->getState());

        $ticket->start();

        $this->assertSame('IN_PROGRESS', $ticket->getState());
    }
}
