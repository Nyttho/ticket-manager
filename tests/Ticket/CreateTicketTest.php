<?php

namespace App\Tests\Ticket;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CreateTicketTest extends WebTestCase
{
    public function testCreateTicket(): void
    {
        $client = static::createClient();

        $client->request(
            'POST',
            '/api/tickets',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'title' => 'Corriger bug production',
                'description' => 'Erreur 500 sur la page de login',
                'priority' => 'haute',
            ])
        );

        $this->assertResponseStatusCodeSame(201);
        $this->assertJson($client->getResponse()->getContent());

        $data = json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('id', $data);
        $this->assertSame('Corriger bug production', $data['title']);
    }
}
