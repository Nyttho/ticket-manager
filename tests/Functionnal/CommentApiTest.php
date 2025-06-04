<?php

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CommentApiTest extends WebTestCase
{
    private function createAuthenticatedClient()
    {
        return static::createClient();
    }

    public function testAddComment()
    {
        $client = $this->createAuthenticatedClient();

        $client->request('POST', '/api/tickets/1/comments', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'content' => 'Super commentaire',
        ]));

        $this->assertResponseStatusCodeSame(201);
    }

    public function testDeleteComment()
    {
        $client = $this->createAuthenticatedClient();

        $client->request('DELETE', '/api/comments/1');

        $this->assertContains($client->getResponse()->getStatusCode(), [204, 403]);
    }
}
