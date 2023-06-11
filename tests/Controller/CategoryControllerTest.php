<?php 

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;

class CategoryControllerTest extends WebTestCase
{
    private $client;
    protected $databaseTool;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->databaseTool = static::getContainer()->get(DatabaseToolCollection::class)->get();
        $this->databaseTool->loadAliceFixture([
            __DIR__. './fixtures/categories.yaml',
        ]);
    }

    public function testGetCategories()
    {
        $this->client->request('GET', '/api/category/');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertJson($this->client->getResponse()->getContent());
    }

    public function testGetOneCategory()
    {
        $this->client->request('GET', '/api/category/1');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertJson($this->client->getResponse()->getContent());
    }

    public function testGetOneCategoryNotFound()
    {
        $this->client->request('GET', '/api/category/999');
        $this->assertEquals(404, $this->client->getResponse()->getStatusCode());
        $this->assertJson($this->client->getResponse()->getContent());
    }

    public function testPostCategory()
    {
        $token = $this->getJwtToken();
        $this->client->request('POST', '/api/category/', [], [], [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_AUTHORIZATION' => 'Bearer ' . $token
        ], json_encode([
            'name' => 'test',
        ]));
        $this->assertEquals(201, $this->client->getResponse()->getStatusCode());
        $this->assertJson($this->client->getResponse()->getContent());
    }

    public function testPostCategoryUnauthorized()
    {
        $this->client->request('POST', '/api/category/', [], [], [
            'CONTENT_TYPE' => 'application/json',
        ], json_encode([
            'name' => 'test',
        ]));
        $this->assertEquals(401, $this->client->getResponse()->getStatusCode());
        $this->assertJson($this->client->getResponse()->getContent());
    }

    public function testPostCategoryForbidden()
    {
        $token = $this->getJwtTokenUser();
        $this->client->request('POST', '/api/category/', [], [], [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_AUTHORIZATION' => 'Bearer ' . $token
        ], json_encode([
            'name' => 'test',
        ]));
        $this->assertEquals(403, $this->client->getResponse()->getStatusCode());
    }

    public function testEditCategory()
    {
        $token = $this->getJwtToken();
        $this->client->request('PUT', '/api/category/1', [], [], [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_AUTHORIZATION' => 'Bearer ' . $token
        ], json_encode([
            'name' => 'test edit',
        ]));
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertJson($this->client->getResponse()->getContent());
    }

    public function testEditCategoryNotFound()
    {
        $token = $this->getJwtToken();
        $this->client->request('PUT', '/api/category/999', [], [], [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_AUTHORIZATION' => 'Bearer ' . $token
        ], json_encode([
            'name' => 'test edit',
        ]));
        $this->assertEquals(404, $this->client->getResponse()->getStatusCode());
        $this->assertJson($this->client->getResponse()->getContent());
    }

    public function testEditCategoryUnauthorized()
    {
        $this->client->request('PUT', '/api/category/1', [], [], [
            'CONTENT_TYPE' => 'application/json',
        ], json_encode([
            'name' => 'test edit',
        ]));
        $this->assertEquals(401, $this->client->getResponse()->getStatusCode());
        $this->assertJson($this->client->getResponse()->getContent());
    }

    public function testEditCategoryForbidden()
    {
        $token = $this->getJwtTokenUser();
        $this->client->request('PUT', '/api/category/1', [], [], [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_AUTHORIZATION' => 'Bearer ' . $token
        ], json_encode([
            'name' => 'test edit',
        ]));
        $this->assertEquals(403, $this->client->getResponse()->getStatusCode());
    }

    public function testDeleteCategory()
    {
        $token = $this->getJwtToken();
        $this->client->request('DELETE', '/api/category/1', [], [], [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_AUTHORIZATION' => 'Bearer ' . $token
        ]);
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertJson($this->client->getResponse()->getContent());
    }

    public function testDeleteCategoryNotFound()
    {
        $token = $this->getJwtToken();
        $this->client->request('DELETE', '/api/category/999', [], [], [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_AUTHORIZATION' => 'Bearer ' . $token
        ]);
        $this->assertEquals(404, $this->client->getResponse()->getStatusCode());
        $this->assertJson($this->client->getResponse()->getContent());
    }

    public function testDeleteCategoryUnauthorized()
    {
        $this->client->request('DELETE', '/api/category/1', [], [], [
            'CONTENT_TYPE' => 'application/json',
        ]);
        $this->assertEquals(401, $this->client->getResponse()->getStatusCode());
        $this->assertJson($this->client->getResponse()->getContent());
    }

    public function testDeleteCategoryForbidden()
    {
        $token = $this->getJwtTokenUser();
        $this->client->request('DELETE', '/api/category/1', [], [], [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_AUTHORIZATION' => 'Bearer ' . $token
        ]);
        $this->assertEquals(403, $this->client->getResponse()->getStatusCode());
    }

    private function getJwtToken(){ // ADMIN
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

    private function getJwtTokenUser(){ // User
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