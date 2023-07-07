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

class PageControllerTest extends WebTestCase
{
    private $client;
    protected $application;
    protected $databaseTool;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->databaseTool = static::getContainer()->get(DatabaseToolCollection::class)->get();
        $this->databaseTool->loadAliceFixture([
            __DIR__. './fixtures/novels.yaml',
        ]);
    }

    public function testCreatePage(){
        $token = $this->getJwtToken();
        $this->client->request('POST', '/api/page',[],[],[
            'CONTENT_TYPE' => 'application/json',
            'HTTP_AUTHORIZATION' => "Bearer ".$token
        ],json_encode([
            "chapter"=> 3,
            "content"=>"test",
            "html"=>"<p>test</p>"
        ]));
        $content = json_decode($this->client->getResponse()->getContent(),true);
        $this->assertEquals(201, $this->client->getResponse()->getStatusCode());
        $this->assertJson($this->client->getResponse()->getContent());
    }

    public function testGetSinglePage()
    {
        $this->client->request('GET', '/api/page/1');
        $content = json_decode($this->client->getResponse()->getContent(),true);
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertJson($this->client->getResponse()->getContent());
    }

    public function testGetSinglePageNotFound()
    {
        $this->client->request('GET', '/api/page/9999');
        $this->assertEquals(404, $this->client->getResponse()->getStatusCode());
        $this->assertJson($this->client->getResponse()->getContent());
    }

    public function testUpdatePage()
    {
        $token = $this->getJwtToken();
        $this->client->request('PUT', '/api/page/1',[],[],[
            'CONTENT_TYPE' => 'application/json',
            'HTTP_AUTHORIZATION' => "Bearer ".$token
        ],json_encode([
            "chapter"=> 3,
            "content"=>"updated",
            "html"=>"<p>updated</p>"
        ]));
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertJson($this->client->getResponse()->getContent());
    }

    public function testDeletePage(){
        $token = $this->getJwtToken();
        $this->client->request('DELETE', '/api/page/1',[],[],[
            'CONTENT_TYPE' => 'application/json',
            'HTTP_AUTHORIZATION' => "Bearer ".$token
        ]);
        $this->assertEquals(204, $this->client->getResponse()->getStatusCode());
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