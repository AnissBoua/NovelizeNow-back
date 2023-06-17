<?php 

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;

class HomeControllerTest extends WebTestCase
{
    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->databaseTool = static::getContainer()->get(DatabaseToolCollection::class)->get();
        $this->data = $this->databaseTool->loadAliceFixture([
            __DIR__. './fixtures/home.yaml',
        ]);
    }

    public function testGetHome(){
        $this->client->request('GET', '/api/home/');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertJson($this->client->getResponse()->getContent());
        $this->assertArrayHasKey('carousel', json_decode($this->client->getResponse()->getContent(), true));
        $this->assertArrayHasKey('chapters', json_decode($this->client->getResponse()->getContent(), true));
        $this->assertArrayHasKey('newNovels', json_decode($this->client->getResponse()->getContent(), true));
        $this->assertArrayHasKey('categories', json_decode($this->client->getResponse()->getContent(), true));
    }
}