<?php

namespace App\Tests\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Doctrine\ORM\EntityManagerInterface;

final class UserControllerTest extends WebTestCase
{
    private $client;
    private EntityManagerInterface $entityManager;
    private string $basePath = '/user/';

    protected function setUp(): void
    {
        $this->client = static::createClient();

        $this->entityManager = static::getContainer()->get('doctrine')->getManager();

        // Nettoyer la table User avant chaque test
        $users = $this->entityManager->getRepository(User::class)->findAll();
        foreach ($users as $user) {
            $this->entityManager->remove($user);
        }
        $this->entityManager->flush();
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->basePath);

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'User index'); // suppose que le titre contient ça
    }

    public function testNew(): void
    {
        $crawler = $this->client->request('GET', $this->basePath . 'new');

        $this->assertResponseIsSuccessful();

        $form = $crawler->selectButton('Save')->form();

        // Remplir le formulaire avec des données valides
        $formData = [
            'user[email]' => 'test@example.com',
            'user[password]' => 'password123',
            'user[name]' => 'Test User',
            // Dates non demandées en formulaire normalement, sinon à adapter
        ];

        $this->client->submit($form, $formData);

        // Doit rediriger vers la liste
        $this->assertResponseRedirects($this->basePath);

        // Suivre la redirection et vérifier la présence dans la liste
        $this->client->followRedirect();

        $this->assertSelectorTextContains('body', 'test@example.com');

        // Vérifier en base
        $userRepo = $this->entityManager->getRepository(User::class);
        $this->assertSame(1, $userRepo->count(['email' => 'test@example.com']));
    }

    public function testShow(): void
    {
        // Créer un user en base
        $user = new User();
        $user->setEmail('show@test.com');
        $user->setPassword('dummy');
        $user->setName('Show User');
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $this->client->request('GET', $this->basePath . $user->getId());

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'User'); // ou autre selon votre template
        $this->assertSelectorTextContains('body', 'show@test.com');
        $this->assertSelectorTextContains('body', 'Show User');
    }

    public function testEdit(): void
    {
        // Créer un user en base
        $user = new User();
        $user->setEmail('edit@test.com');
        $user->setPassword('dummy');
        $user->setName('Old Name');
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $crawler = $this->client->request('GET', $this->basePath . $user->getId() . '/edit');

        $this->assertResponseIsSuccessful();

        $form = $crawler->selectButton('Update')->form();

        $formData = [
            'user[email]' => 'edit@test.com', // garder l'email sinon problème d'unicité
            'user[password]' => 'newpassword',
            'user[name]' => 'New Name',
        ];

        $this->client->submit($form, $formData);

        $this->assertResponseRedirects($this->basePath);

        $this->client->followRedirect();

        // Vérifier la mise à jour en base
        $updatedUser = $this->entityManager->getRepository(User::class)->find($user->getId());
        $this->assertSame('New Name', $updatedUser->getName());
        $this->assertSame('edit@test.com', $updatedUser->getEmail());
        $this->assertSame('newpassword', $updatedUser->getPassword());
    }

    public function testDelete(): void
    {
        // Créer un user en base
        $user = new User();
        $user->setEmail('delete@test.com');
        $user->setPassword('dummy');
        $user->setName('To Delete');
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $crawler = $this->client->request('GET', $this->basePath . $user->getId());

        $form = $crawler->selectButton('Delete')->form();

        $this->client->submit($form);

        $this->assertResponseRedirects($this->basePath);

        $this->client->followRedirect();

        // Vérifier que l'utilisateur a bien été supprimé
        $deletedUser = $this->entityManager->getRepository(User::class)->find($user->getId());
        $this->assertNull($deletedUser);
    }
}
