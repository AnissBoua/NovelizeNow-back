<?php 

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;

class UserNovelControllerTest extends WebTestCase
{
    private $client;
    protected $databaseTool;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->databaseTool = static::getContainer()->get(DatabaseToolCollection::class)->get();
        $this->databaseTool->loadAliceFixture([
            __DIR__. './fixtures/novels.yaml',
        ]);
    }

    public function testGetNovelsByUser(){
        $token = $this->getJwtToken();
        $this->client->request('GET', '/api/user/novels', [], [], [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_AUTHORIZATION' => 'Bearer ' . $token
        ]);
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertJson($this->client->getResponse()->getContent());
    }

    public function testGetNovelsByUserUnauthorized(){
        $this->client->request('GET', '/api/user/novels', [], [], [
            'CONTENT_TYPE' => 'application/json',
        ]);
        $this->assertEquals(401, $this->client->getResponse()->getStatusCode());
        $this->assertJson($this->client->getResponse()->getContent());
    }

    private function getJwtToken(){
        $this->client->request('POST', '/api/login', [], [], [
            'CONTENT_TYPE' => 'application/json'
        ], 
        json_encode([
            'email' => 'user1@gmail.com',
            'password' => 'password',
        ]));

        $response = json_decode($this->client->getResponse()->getContent());
        return $response->token; 
    }
}