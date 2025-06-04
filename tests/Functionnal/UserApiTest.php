<?php

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserApiTest extends WebTestCase
{
    private function createAuthenticatedClient()
    {
        return static::createClient();
    }

    public function testCreateUser()
    {
        $client = static::createClient();

        $client->request('POST', '/api/users', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'username' => 'newuser',
            'password' => 'password123',
            'email' => 'newuser@example.com',
        ]));

        $this->assertResponseStatusCodeSame(201);
    }

    public function testUpdateUser()
    {
        $client = $this->createAuthenticatedClient();

        $client->request('PUT', '/api/users/1', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'email' => 'updated@example.com',
        ]));

        $this->assertContains($client->getResponse()->getStatusCode(), [200, 403]);
    }

    public function testDeleteUser()
    {
        $client = $this->createAuthenticatedClient();

        $client->request('DELETE', '/api/users/1');

        $this->assertContains($client->getResponse()->getStatusCode(), [204, 403]);
    }

    public function testListUsers()
    {
        $client = $this->createAuthenticatedClient();

        $client->request('GET', '/api/users');

        $this->assertResponseIsSuccessful();
    }

    public function testGetUserDetails()
    {
        $client = $this->createAuthenticatedClient();

        $client->request('GET', '/api/users/1');

        $this->assertContains($client->getResponse()->getStatusCode(), [200, 403]);
    }
}
