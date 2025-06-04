<?php

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AuthApiTest extends WebTestCase
{
    public function testLogin()
    {
        $client = static::createClient();

        $client->request('POST', '/api/login', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'username' => 'user1',
            'password' => 'pass',
        ]));

        $this->assertResponseIsSuccessful();
        $content = json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('token', $content);
    }

    public function testLogout()
    {
        $client = static::createClient();

        //simuler la présence d’un token

        $client->request('POST', '/api/logout', [], [], [
            'HTTP_Authorization' => 'Bearer faketoken'
        ]);

        $this->assertResponseIsSuccessful();
    }

    public function testGetCurrentUser()
    {
        $client = static::createClient();

        // Simuler un token valide

        $client->request('GET', '/api/me', [], [], [
            'HTTP_Authorization' => 'Bearer faketoken'
        ]);

        $this->assertResponseIsSuccessful();
        $content = json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('username', $content);
    }
}
