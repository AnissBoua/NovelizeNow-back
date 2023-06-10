<?php 

namespace App\Tests\Controller;

use App\Entity\Novel;
use App\Entity\Category;
use App\DataFixtures\NovelFixture;
use Doctrine\Common\DataFixtures\Loader;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;

class NovelControllerTest extends WebTestCase
{
    private $client;
    protected $application;
    protected $databaseTool;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->databaseTool = static::getContainer()->get(DatabaseToolCollection::class)->get();
        $this->databaseTool->loadAliceFixture([
            __DIR__. './novels.yaml',
        ]);
    }

    public function testGetNovels()
    {
        $this->client->request('GET', '/api/novel/');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertJson($this->client->getResponse()->getContent());
    }

    public function testGetSingleNovel()
    {
        $this->client->request('GET', '/api/novel/1');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertJson($this->client->getResponse()->getContent());
    }

    public function testGetSingleNovelNotFound()
    {
        $this->client->request('GET', '/api/novel/9999');
        $this->assertEquals(404, $this->client->getResponse()->getStatusCode());
        $this->assertJson($this->client->getResponse()->getContent());
    }

    public function testGetSingleNovelBySlug()
    {
        $this->client->request('GET', '/api/novel/bySlug/novel-1');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertJson($this->client->getResponse()->getContent());
    }

    public function testGetSingleNovelBySlugNotFound()
    {
        $this->client->request('GET', '/api/novel/bySlug/novel-9999');
        $this->assertEquals(404, $this->client->getResponse()->getStatusCode());
        $this->assertJson($this->client->getResponse()->getContent());
    }

    public function testGetNovelsBySearch()
    {
        $this->client->request('GET', '/api/novel/search?search=Novel%201');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertJson($this->client->getResponse()->getContent());
    }

    public function testGetNovelsBySearchNotFound()
    {
        $this->client->request('GET', '/api/novel/search?search=Novel%9999');
        $this->assertEquals(404, $this->client->getResponse()->getStatusCode());
        $this->assertJson($this->client->getResponse()->getContent());
    }

    public function testGetNovelsBySearchBadRequest()
    {
        $this->client->request('GET', '/api/novel/search');
        $this->assertEquals(400, $this->client->getResponse()->getStatusCode());
        $this->assertJson($this->client->getResponse()->getContent());
    }

    public function testPostNovel()
    {
        $token = $this->getJwtToken();
        $this->client->request('POST', '/api/novel/', [], [], [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_AUTHORIZATION' => 'Bearer ' . $token,
        ], 
        json_encode([
            'title' => 'Novel 4',
            'resume' => 'Description 4',
            'status' => 'unpublished',
            'categories' => [1, 2],
            'price' => 100,
        ]));
        $this->assertEquals(201, $this->client->getResponse()->getStatusCode());
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