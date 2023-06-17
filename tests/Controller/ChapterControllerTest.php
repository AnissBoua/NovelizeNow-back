<?php 

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;


class ChapterControllerTest extends WebTestCase {
    private $client;
    protected $databaseTool;
    protected $data;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->databaseTool = static::getContainer()->get(DatabaseToolCollection::class)->get();
        $this->data = $this->databaseTool->loadAliceFixture([
            __DIR__. './fixtures/chapter.yaml',
        ]);
    }

    public function testGetChapter(){
        $this->client->request('GET', '/api/chapter/1');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertJson($this->client->getResponse()->getContent());
    }

    public function testGetChapterNotFound(){
        $this->client->request('GET', '/api/chapter/999');
        $this->assertEquals(404, $this->client->getResponse()->getStatusCode());
        $this->assertJson($this->client->getResponse()->getContent());
    }

    public function testPostChapter(){
        $token = $this->getJwtToken();
        $this->client->request('POST', '/api/chapter', [], [], [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_AUTHORIZATION' => 'Bearer ' . $token
        ], json_encode([
            'novel' => 1,
            'title' => 'Chapter 1',
            'status' => 'published',
        ]));
        $this->assertEquals(201, $this->client->getResponse()->getStatusCode());
        $this->assertJson($this->client->getResponse()->getContent());
    }

    public function testPostChapterUnauthorized(){
        $this->client->request('POST', '/api/chapter', [], [], [
            'CONTENT_TYPE' => 'application/json',
        ], json_encode([
            'novel' => 1,
            'title' => 'Chapter 1',
            'status' => 'published',
        ]));
        $this->assertEquals(401, $this->client->getResponse()->getStatusCode());
        $this->assertJson($this->client->getResponse()->getContent());
    }

    public function testPostChapterForbidden(){
        $token = $this->getJwtTokenForbidden();
        $this->client->request('POST', '/api/chapter', [], [], [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_AUTHORIZATION' => 'Bearer ' . $token
        ], json_encode([
            'novel' => 1,
            'title' => 'Chapter 1',
            'status' => 'published',
        ]));
        $this->assertEquals(403, $this->client->getResponse()->getStatusCode());
        $this->assertJson($this->client->getResponse()->getContent());
    }

    public function testPostChapterNotFound(){
        $token = $this->getJwtToken();
        $this->client->request('POST', '/api/chapter', [], [], [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_AUTHORIZATION' => 'Bearer ' . $token
        ], json_encode([
            'novel' => 999,
            'title' => 'Chapter 1',
            'status' => 'published',
        ]));
        $this->assertEquals(404, $this->client->getResponse()->getStatusCode());
        $this->assertJson($this->client->getResponse()->getContent());
    }

    public function testPutChapter(){
        $token = $this->getJwtToken();
        $this->client->request('PUT', '/api/chapter/1', [], [], [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_AUTHORIZATION' => 'Bearer ' . $token
        ], json_encode([
            'title' => 'Chapter 1',
            'status' => 'published',
            'pageState' => [1, 2],
        ]));
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertJson($this->client->getResponse()->getContent());
    }

    public function testPutChapterUnauthorized(){
        $this->client->request('PUT', '/api/chapter/1', [], [], [
            'CONTENT_TYPE' => 'application/json',
        ], json_encode([
            'title' => 'Chapter 1',
            'status' => 'published',
            'pageState' => [1, 2],
        ]));
        $this->assertEquals(401, $this->client->getResponse()->getStatusCode());
        $this->assertJson($this->client->getResponse()->getContent());
    }

    public function testPutChapterNotFound(){
        $token = $this->getJwtToken();
        $this->client->request('PUT', '/api/chapter/9999', [], [], [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_AUTHORIZATION' => 'Bearer ' . $token
        ], json_encode([
            'title' => 'Chapter 1',
            'status' => 'published',
            'pageState' => [1, 2],
        ]));
        $this->assertEquals(404, $this->client->getResponse()->getStatusCode());
        $this->assertJson($this->client->getResponse()->getContent());
    }

    public function testPutChapterForbidden(){
        $token = $this->getJwtTokenForbidden();
        $this->client->request('PUT', '/api/chapter/1', [], [], [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_AUTHORIZATION' => 'Bearer ' . $token
        ], json_encode([
            'title' => 'Chapter 1',
            'status' => 'published',
            'pageState' => [1, 2],
        ]));
        $this->assertEquals(403, $this->client->getResponse()->getStatusCode());
        $this->assertJson($this->client->getResponse()->getContent());
    }

    public function testDeleteChapter(){
        $token = $this->getJwtToken();
        $this->client->request('DELETE', '/api/chapter/1', [], [], [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_AUTHORIZATION' => 'Bearer ' . $token
        ]);
        $this->assertEquals(204, $this->client->getResponse()->getStatusCode());
    }

    public function testDeleteChapterUnauthorized(){
        $this->client->request('DELETE', '/api/chapter/1', [], [], [
            'CONTENT_TYPE' => 'application/json',
        ]);
        $this->assertEquals(401, $this->client->getResponse()->getStatusCode());
        $this->assertJson($this->client->getResponse()->getContent());
    }

    public function testDeleteChapterNotFound(){
        $token = $this->getJwtToken();
        $this->client->request('DELETE', '/api/chapter/999', [], [], [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_AUTHORIZATION' => 'Bearer ' . $token
        ]);
        $this->assertEquals(404, $this->client->getResponse()->getStatusCode());
        $this->assertJson($this->client->getResponse()->getContent());
    }

    public function testDeleteChapterForbidden(){
        $token = $this->getJwtTokenForbidden();
        $this->client->request('DELETE', '/api/chapter/1', [], [], [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_AUTHORIZATION' => 'Bearer ' . $token
        ]);
        $this->assertEquals(403, $this->client->getResponse()->getStatusCode());
        $this->assertJson($this->client->getResponse()->getContent());
    }

    public function testGetChapterPages(){
        $token = $this->getJwtToken();
        $this->client->request('GET', '/api/chapter_pages/1', [], [], [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_AUTHORIZATION' => 'Bearer ' . $token
        ]);
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    public function testGetChapterPagesUnauthorized(){
        $this->client->request('GET', '/api/chapter_pages/1', [], [], [
            'CONTENT_TYPE' => 'application/json',
        ]);
        $this->assertEquals(401, $this->client->getResponse()->getStatusCode());
    }

    public function testGetChapterPagesNotFound(){
        $token = $this->getJwtToken();
        $this->client->request('GET', '/api/chapter_pages/999', [], [], [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_AUTHORIZATION' => 'Bearer ' . $token
        ]);
        $this->assertEquals(404, $this->client->getResponse()->getStatusCode());
    }

    public function testGetChapterPagesForbidden(){
        $token = $this->getJwtToken();
        $this->client->request('GET', '/api/chapter_pages/2', [], [], [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_AUTHORIZATION' => 'Bearer ' . $token
        ]);
        $this->assertEquals(403, $this->client->getResponse()->getStatusCode());
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

    private function getJwtTokenForbidden(){
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
}