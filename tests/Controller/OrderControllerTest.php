<?php 

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;

class OrderControllerTest extends WebTestCase
{
    private $client;
    protected $databaseTool;
    protected $data;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->databaseTool = static::getContainer()->get(DatabaseToolCollection::class)->get();
        $this->data = $this->databaseTool->loadAliceFixture([
            __DIR__. './fixtures/order.yaml',
        ]);
    }

    public function testPostOrder(){
        $token = $this->getJwtToken();
        $this->client->request('POST', '/api/order/', [], [], [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_AUTHORIZATION' => 'Bearer ' . $token
        ], json_encode([
            'novel' => 1,
        ]));
        $this->assertEquals(201, $this->client->getResponse()->getStatusCode());
        $this->assertJson($this->client->getResponse()->getContent());
    }

    public function testPostOrderUnauthorized(){
        $this->client->request('POST', '/api/order/', [], [], [
            'CONTENT_TYPE' => 'application/json',
        ], json_encode([
            'novel' => 1,
        ]));
        $this->assertEquals(401, $this->client->getResponse()->getStatusCode());
        $this->assertJson($this->client->getResponse()->getContent());
    }

    public function testPostOrderNotFound(){
        $token = $this->getJwtToken();
        $this->client->request('POST', '/api/order/', [], [], [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_AUTHORIZATION' => 'Bearer ' . $token
        ], json_encode([
            'novel' => 999,
        ]));
        $this->assertEquals(404, $this->client->getResponse()->getStatusCode());
        $this->assertJson($this->client->getResponse()->getContent());
    }

    public function testPostOrderLowCoins(){
        $token = $this->getJwtTokenNoCoins();
        $this->client->request('POST', '/api/order/', [], [], [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_AUTHORIZATION' => 'Bearer ' . $token
        ], json_encode([
            'novel' => 1,
        ]));
        $this->assertEquals(400, $this->client->getResponse()->getStatusCode());
        $this->assertJson($this->client->getResponse()->getContent());
        $this->assertEquals('You don\'t have enough coins to buy this Novel.', json_decode($this->client->getResponse()->getContent())->message );

    }

    public function testPostOrderAlreadyBought(){
        $token = $this->getJwtTokenAlreadyBought();
        $this->client->request('POST', '/api/order/', [], [], [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_AUTHORIZATION' => 'Bearer ' . $token
        ], json_encode([
            'novel' => 1,
        ]));
        $this->assertEquals(400, $this->client->getResponse()->getStatusCode());
        $this->assertJson($this->client->getResponse()->getContent());
        $this->assertEquals('You already bought this Novel.', json_decode($this->client->getResponse()->getContent())->message );
    }

    public function testGetUserBoughtNovel(){
        $token = $this->getJwtTokenAlreadyBought();
        $this->client->request('GET', '/api/order/novel/novel-1', [], [], [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_AUTHORIZATION' => 'Bearer ' . $token
        ]);
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertJson($this->client->getResponse()->getContent());
        $this->assertTrue(json_decode($this->client->getResponse()->getContent())->bought);
    }

    public function testGetUserBoughtNovelFalse(){
        $token = $this->getJwtToken();
        $this->client->request('GET', '/api/order/novel/novel-1', [], [], [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_AUTHORIZATION' => 'Bearer ' . $token
        ]);
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertJson($this->client->getResponse()->getContent());
        $this->assertFalse(json_decode($this->client->getResponse()->getContent())->bought);
    }

    public function testGetUserBoughtNovelUnauthorized(){
        $this->client->request('GET', '/api/order/novel/novel-1', [], [], [
            'CONTENT_TYPE' => 'application/json',
        ]);
        $this->assertEquals(401, $this->client->getResponse()->getStatusCode());
        $this->assertJson($this->client->getResponse()->getContent());
    }

    public function testGetUserBoughtNovelNotFound(){
        $token = $this->getJwtTokenAlreadyBought();
        $this->client->request('GET', '/api/order/novel/novel-999', [], [], [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_AUTHORIZATION' => 'Bearer ' . $token
        ]);
        $this->assertEquals(404, $this->client->getResponse()->getStatusCode());
        $this->assertJson($this->client->getResponse()->getContent());
    }

    private function getJwtToken(){
        $this->client->request('POST', '/api/login', [], [], [
            'CONTENT_TYPE' => 'application/json'
        ], 
        json_encode([
            'email' => 'user2@gmail.com',
            'password' => 'password',
        ]));

        $response = json_decode($this->client->getResponse()->getContent());
        return $response->token; 
    }

    private function getJwtTokenNoCoins(){
        $this->client->request('POST', '/api/login', [], [], [
            'CONTENT_TYPE' => 'application/json'
        ], 
        json_encode([
            'email' => 'user3@gmail.com',
            'password' => 'password',
        ]));

        $response = json_decode($this->client->getResponse()->getContent());
        return $response->token; 
    }

    private function getJwtTokenAlreadyBought(){
        $this->client->request('POST', '/api/login', [], [], [
            'CONTENT_TYPE' => 'application/json'
        ], 
        json_encode([
            'email' => 'user4@gmail.com',
            'password' => 'password',
        ]));

        $response = json_decode($this->client->getResponse()->getContent());
        return $response->token; 
    }
}