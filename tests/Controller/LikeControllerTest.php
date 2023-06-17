<?php 

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;

class LikeControllerTest extends WebTestCase
{
    private $client;
    protected $databaseTool;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->databaseTool = static::getContainer()->get(DatabaseToolCollection::class)->get();
        $this->databaseTool->loadAliceFixture([
            __DIR__. './fixtures/likes.yaml',
        ]);
    }

    public function testPostLike()
    {
        $token = $this->getJwtToken();
        $this->client->request('POST', '/api/like/', [], [], [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_AUTHORIZATION' => 'Bearer ' . $token
        ], json_encode([
            'novel' => 1,
        ]));
        $this->assertEquals(201, $this->client->getResponse()->getStatusCode());
        $this->assertJson($this->client->getResponse()->getContent());

        // remove like
        $this->client->request('POST', '/api/like/', [], [], [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_AUTHORIZATION' => 'Bearer ' . $token
        ], json_encode([
            'novel' => 1,
        ]));
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertJson($this->client->getResponse()->getContent());
    }

    public function testPostLikeUnauthorized()
    {
        $this->client->request('POST', '/api/like/', [], [], [
            'CONTENT_TYPE' => 'application/json',
        ], json_encode([
            'novel' => 1,
        ]));
        $this->assertEquals(401, $this->client->getResponse()->getStatusCode());
        $this->assertJson($this->client->getResponse()->getContent());
    }

    public function testPostLikeNotFound()
    {
        $token = $this->getJwtToken();
        $this->client->request('POST', '/api/like/', [], [], [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_AUTHORIZATION' => 'Bearer ' . $token
        ], json_encode([
            'novel' => 999,
        ]));
        $this->assertEquals(404, $this->client->getResponse()->getStatusCode());
        $this->assertJson($this->client->getResponse()->getContent());
    }

    public function testGetNovelLikesCount()
    {
        $this->client->request('GET', '/api/like/count/1', [], [], [
            'CONTENT_TYPE' => 'application/json',
        ]);
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertJson($this->client->getResponse()->getContent());
        $this->assertEquals(4, json_decode($this->client->getResponse()->getContent())->count);
    }

    public function testGetUserLikedNovel()
    {
        $token = $this->getJwtToken();
        $this->client->request('GET', '/api/like/liked/1', [], [], [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_AUTHORIZATION' => 'Bearer ' . $token
        ]);
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertJson($this->client->getResponse()->getContent());
        $this->assertFalse(json_decode($this->client->getResponse()->getContent())->liked);
    }

    public function testGetUserLikedNovelUnauthorized()
    {
        $this->client->request('GET', '/api/like/liked/1', [], [], [
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