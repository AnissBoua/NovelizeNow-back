<?php 

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;

class NovelControllerTest extends WebTestCase
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
        $this->client->request('POST', '/api/novel/', [
            'title' => 'Novel 4',
            'resume' => 'Description 4',
            'status' => 'unpublished',
            'category' => [1, 2],
            'price' => 100,
        ], [], [
            'CONTENT_TYPE' => 'multipart/form-data',
            'HTTP_AUTHORIZATION' => 'Bearer ' . $token,
        ]);
        $this->assertEquals(201, $this->client->getResponse()->getStatusCode());
        $this->assertJson($this->client->getResponse()->getContent());
    }

    public function testPostNovelNoToken()
    {
        $this->client->request('POST', '/api/novel/', [
            'title' => 'Novel 4',
            'resume' => 'Description 4',
            'status' => 'unpublished',
            'category' => [1, 2],
            'price' => 100,
        ], [], [
            'CONTENT_TYPE' => 'multipart/form-data',
        ]);
        $this->assertEquals(401, $this->client->getResponse()->getStatusCode());
        $this->assertJson($this->client->getResponse()->getContent());
    }

    public function testPostNovelWithFiles()
    {
        $token = $this->getJwtToken();
        $cover = new UploadedFile('public\uploads\test\20230518101205-A8w4O3p6bC9yQ2nA-zelda-breath-of-the-wild.png', '20230518101205-A8w4O3p6bC9yQ2nA-zelda-breath-of-the-wild.png');
        $banner = new UploadedFile('public\uploads\test\20230518101205-A8w4O3p6bC9yQ2nA-zelda-breath-of-the-wild-banner.png', '20230518101205-A8w4O3p6bC9yQ2nA-zelda-breath-of-the-wild-banner.png');
        $this->client->request('POST', '/api/novel/', [
            'title' => 'Novel 4',
            'resume' => 'Description 4',
            'status' => 'unpublished',
            'category' => [1, 2],
            'price' => 100,
        ], 
        [
            'cover' => $cover,
            'banner' => $banner,
        ], 
        [
            'CONTENT_TYPE' => 'multipart/form-data',
            'HTTP_AUTHORIZATION' => 'Bearer ' . $token,
        ]);
        $this->assertEquals(201, $this->client->getResponse()->getStatusCode());
        $this->assertJson($this->client->getResponse()->getContent());
    }

    public function testEditNovel()
    {
        $token = $this->getJwtToken();
        // POST car c'est du multipart/form-data
        $this->client->request('POST', '/api/novel/1', [
            'title' => 'Novel 2',
            'resume' => 'Description 2',
            'status' => 'published',
            'category' => [3, 4],
            'price' => 98,
        ], [], [
            'CONTENT_TYPE' => 'multipart/form-data',
            'HTTP_AUTHORIZATION' => 'Bearer ' . $token,
        ]);
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertJson($this->client->getResponse()->getContent());
    }

    public function testEditNovelWithFiles()
    {
        $token = $this->getJwtToken();
        $cover = new UploadedFile('public\uploads\test\20230518101205-A8w4O3p6bC9yQ2nA-zelda-breath-of-the-wild.png', '20230518101205-A8w4O3p6bC9yQ2nA-zelda-breath-of-the-wild.png');
        $banner = new UploadedFile('public\uploads\test\20230518101205-A8w4O3p6bC9yQ2nA-zelda-breath-of-the-wild-banner.png', '20230518101205-A8w4O3p6bC9yQ2nA-zelda-breath-of-the-wild-banner.png');
        // POST car c'est du multipart/form-data
        $this->client->request('POST', '/api/novel/1', [
            'title' => 'Novel 2',
            'resume' => 'Description 2',
            'status' => 'published',
            'category' => [3, 4],
            'price' => 98,
        ], 
        [
            'cover' => $cover,
            'banner' => $banner,
        ], 
        [
            'CONTENT_TYPE' => 'multipart/form-data',
            'HTTP_AUTHORIZATION' => 'Bearer ' . $token,
        ]);
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertJson($this->client->getResponse()->getContent());
    }

    public function testEditNovelNotFound()
    {
        $token = $this->getJwtToken();
        // POST car c'est du multipart/form-data
        $this->client->request('POST', '/api/novel/999', [
            'title' => 'Novel 2',
            'resume' => 'Description 2',
            'status' => 'published',
            'category' => [3, 4],
            'price' => 98,
        ], [], [
            'CONTENT_TYPE' => 'multipart/form-data',
            'HTTP_AUTHORIZATION' => 'Bearer ' . $token,
        ]);
        $this->assertEquals(404, $this->client->getResponse()->getStatusCode());
        $this->assertJson($this->client->getResponse()->getContent());
    }

    public function testEditNovelNoToken()
    {
        // POST car c'est du multipart/form-data
        $this->client->request('POST', '/api/novel/1', [
            'title' => 'Novel 2',
            'resume' => 'Description 2',
            'status' => 'published',
            'category' => [3, 4],
            'price' => 98,
        ], [], [
            'CONTENT_TYPE' => 'multipart/form-data',
        ]);
        $this->assertEquals(401, $this->client->getResponse()->getStatusCode());
        $this->assertJson($this->client->getResponse()->getContent());
    }

    public function testEditNovelInvalidUser()
    {
        $token = $this->getInvalidUserJwtToken();
        // POST car c'est du multipart/form-data
        $this->client->request('POST', '/api/novel/1', [
            'title' => 'Novel 2',
            'resume' => 'Description 2',
            'status' => 'published',
            'category' => [3, 4],
            'price' => 98,
        ], [], [
            'CONTENT_TYPE' => 'multipart/form-data',
            'HTTP_AUTHORIZATION' => 'Bearer ' . $token,
        ]);
        $this->assertEquals(401, $this->client->getResponse()->getStatusCode());
        $this->assertJson($this->client->getResponse()->getContent());
    }

    public function testDeleteNovel()
    {

        $token = $this->getJwtToken();
        $this->client->request('DELETE', '/api/novel/1', [], [], [
            'CONTENT_TYPE' => 'multipart/form-data',
            'HTTP_AUTHORIZATION' => 'Bearer ' . $token,
        ]);
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertJson($this->client->getResponse()->getContent());
    }

    public function testDeleteNovelNotFound()
    {

        $token = $this->getJwtToken();
        $this->client->request('DELETE', '/api/novel/999', [], [], [
            'CONTENT_TYPE' => 'multipart/form-data',
            'HTTP_AUTHORIZATION' => 'Bearer ' . $token,
        ]);
        $this->assertEquals(404, $this->client->getResponse()->getStatusCode());
        $this->assertJson($this->client->getResponse()->getContent());
    }

    public function testDeleteNovelNoToken()
    {

        $this->client->request('DELETE', '/api/novel/1', [], [], [
            'CONTENT_TYPE' => 'multipart/form-data',
        ]);
        $this->assertEquals(401, $this->client->getResponse()->getStatusCode());
        $this->assertJson($this->client->getResponse()->getContent());
    }

    public function testDeleteNovelInvalidUser()
    {
        $token = $this->getInvalidUserJwtToken();
        $this->client->request('DELETE', '/api/novel/1', [], [], [
            'CONTENT_TYPE' => 'multipart/form-data',
            'HTTP_AUTHORIZATION' => 'Bearer ' . $token,
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

    private function getInvalidUserJwtToken(){
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