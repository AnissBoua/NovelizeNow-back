<?php 

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;

class CommentControllerTest extends WebTestCase
{
    private $client;
    protected $databaseTool;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->databaseTool = static::getContainer()->get(DatabaseToolCollection::class)->get();
        $this->databaseTool->loadAliceFixture([
            __DIR__. './fixtures/comments.yaml',
        ]);
    }

    public function testPostComment()
    {
        $token = $this->getJwtToken();
        $this->client->request('POST', '/api/comment/', [], [], [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_AUTHORIZATION' => 'Bearer ' . $token
        ], json_encode([
            'novel' => 1,
            'content' => 'test',
        ]));
        $this->assertEquals(201, $this->client->getResponse()->getStatusCode());
        $this->assertJson($this->client->getResponse()->getContent());
    }

    public function testPostCommentUnauthorized()
    {
        $this->client->request('POST', '/api/comment/', [], [], [
            'CONTENT_TYPE' => 'application/json',
        ], json_encode([
            'novel' => 1,
            'content' => 'test',
        ]));
        $this->assertEquals(401, $this->client->getResponse()->getStatusCode());
        $this->assertJson($this->client->getResponse()->getContent());
    }

    public function testPostCommentNotFound()
    {
        $token = $this->getJwtToken();
        $this->client->request('POST', '/api/comment/', [], [], [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_AUTHORIZATION' => 'Bearer ' . $token
        ], json_encode([
            'novel' => 999,
            'content' => 'test',
        ]));
        $this->assertEquals(404, $this->client->getResponse()->getStatusCode());
        $this->assertJson($this->client->getResponse()->getContent());
    }

    public function testPostCommentBlockRecursive()
    {
        $token = $this->getJwtToken();
        $this->client->request('POST', '/api/comment/', [], [], [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_AUTHORIZATION' => 'Bearer ' . $token
        ], json_encode([
            'novel' => 1,
            'content' => 'test',
            'parent' => 6,
        ]));
        $this->assertEquals(400, $this->client->getResponse()->getStatusCode());
        $this->assertJson($this->client->getResponse()->getContent());
    }

    public function testEditComment()
    {
        $token = $this->getJwtToken();
        $this->client->request('PUT', '/api/comment/1', [], [], [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_AUTHORIZATION' => 'Bearer ' . $token
        ], json_encode([
            'novel' => 1,
            'content' => 'test edit',
        ]));
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertJson($this->client->getResponse()->getContent());
    }

    public function testEditCommentNotFoundComment()
    {
        $token = $this->getJwtToken();
        $this->client->request('PUT', '/api/comment/999', [], [], [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_AUTHORIZATION' => 'Bearer ' . $token
        ], json_encode([
            'novel' => 1,
            'content' => 'test edit',
        ]));
        $this->assertEquals(404, $this->client->getResponse()->getStatusCode());
        $this->assertJson($this->client->getResponse()->getContent());
    }

    public function testEditCommentUnauthorized()
    {
        $this->client->request('PUT', '/api/comment/1', [], [], [
            'CONTENT_TYPE' => 'application/json',
        ], json_encode([
            'novel' => 1,
            'content' => 'test edit',
        ]));
        $this->assertEquals(401, $this->client->getResponse()->getStatusCode());
        $this->assertJson($this->client->getResponse()->getContent());
    }

    public function testEditCommentForbidden()
    {
        $token = $this->getJwtToken();
        $this->client->request('PUT', '/api/comment/2', [], [], [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_AUTHORIZATION' => 'Bearer ' . $token
        ], json_encode([
            'novel' => 1,
            'content' => 'test edit',
        ]));
        $this->assertEquals(403, $this->client->getResponse()->getStatusCode());
        $this->assertJson($this->client->getResponse()->getContent());
    }

    public function testDeleteComment()
    {
        $token = $this->getJwtToken();
        $this->client->request('DELETE', '/api/comment/1', [], [], [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_AUTHORIZATION' => 'Bearer ' . $token
        ]);
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertJson($this->client->getResponse()->getContent());
    }

    public function testDeleteCommentNotFoundComment()
    {
        $token = $this->getJwtToken();
        $this->client->request('DELETE', '/api/comment/999', [], [], [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_AUTHORIZATION' => 'Bearer ' . $token
        ]);
        $this->assertEquals(404, $this->client->getResponse()->getStatusCode());
        $this->assertJson($this->client->getResponse()->getContent());
    }

    public function testDeleteCommentUnauthorized()
    {
        $this->client->request('DELETE', '/api/comment/1', [], [], [
            'CONTENT_TYPE' => 'application/json',
        ]);
        $this->assertEquals(401, $this->client->getResponse()->getStatusCode());
        $this->assertJson($this->client->getResponse()->getContent());
    }

    public function testDeleteCommentForbidden()
    {
        $token = $this->getJwtToken();
        $this->client->request('PUT', '/api/comment/2', [], [], [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_AUTHORIZATION' => 'Bearer ' . $token
        ]);
        $this->assertEquals(403, $this->client->getResponse()->getStatusCode());
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