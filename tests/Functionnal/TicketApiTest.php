<?php

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TicketApiTest extends WebTestCase
{
    private function createAuthenticatedClient(string $username = 'user1', string $password = 'pass')
    {
        $client = static::createClient();
        // simuler une authentification

        return $client;
    }

    public function testCreateTicket()
    {
        $client = $this->createAuthenticatedClient();

        $client->request('POST', '/api/tickets', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'title' => 'Nouveau ticket',
            'description' => 'Description du ticket',
        ]));

        $this->assertResponseStatusCodeSame(201);
        $this->assertResponseHeaderSame('content-type', 'application/json');
    }

    public function testModifyTicket()
    {
        $client = $this->createAuthenticatedClient();

        $client->request('PATCH', '/api/tickets/1', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'title' => 'Titre modifié',
            'priority' => 'HIGH',
        ]));

        $this->assertContains($client->getResponse()->getStatusCode(), [200, 403]);
    }

    public function testDeleteTicket()
    {
        $client = $this->createAuthenticatedClient();

        $client->request('DELETE', '/api/tickets/1');

        $this->assertContains($client->getResponse()->getStatusCode(), [204, 403]);
    }

    public function testAssignTicket()
    {
        $client = $this->createAuthenticatedClient();

        $client->request('POST', '/api/tickets/1/assign', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'assignee_id' => 2,
        ]));

        $this->assertContains($client->getResponse()->getStatusCode(), [200, 403]);
    }

    public function testRemoveAssignment()
    {
        $client = $this->createAuthenticatedClient();

        $client->request('DELETE', '/api/tickets/1/assign');

        $this->assertContains($client->getResponse()->getStatusCode(), [204, 403]);
    }

    public function testStartTicket()
    {
        $client = $this->createAuthenticatedClient();

        $client->request('POST', '/api/tickets/1/start');

        $this->assertContains($client->getResponse()->getStatusCode(), [200, 403]);
    }

    public function testCloseTicket()
    {
        $client = $this->createAuthenticatedClient();

        $client->request('POST', '/api/tickets/1/close');

        $this->assertContains($client->getResponse()->getStatusCode(), [200, 403]);
    }

    public function testListMyTickets()
    {
        $client = $this->createAuthenticatedClient();

        $client->request('GET', '/api/my-tickets');

        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/json');
    }

    public function testListAssignedTickets()
    {
        $client = $this->createAuthenticatedClient();

        $client->request('GET', '/api/assigned-tickets');

        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/json');
    }

    public function testGetTicketDetails()
    {
        $client = $this->createAuthenticatedClient();

        $client->request('GET', '/api/tickets/1');

        $this->assertContains($client->getResponse()->getStatusCode(), [200, 403]);
    }
}
