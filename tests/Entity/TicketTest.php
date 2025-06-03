<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;
use App\Entity\Ticket;

class TicketTest extends TestCase
{
    public function testStartChangesStateToInProgress(): void
    {
        $ticket = new Ticket('Titre', 'Description', 'normale');

        var_dump($ticket->getState());
        $this->assertSame('PENDING', $ticket->getState());

        $ticket->start();

        $this->assertSame('IN_PROGRESS', $ticket->getState());
    }
}
