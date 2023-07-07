<?php 

namespace App\Tests\Controller;

use Doctrine\Common\DataFixtures\Loader;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;

class StripeControllerTest extends WebTestCase
{
    private $client;
    protected $application;
    protected $databaseTool;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->databaseTool = static::getContainer()->get(DatabaseToolCollection::class)->get();
        $this->databaseTool->loadAliceFixture([
            __DIR__. './fixtures/stripe.yaml',
        ]);
    }

    public function testCreateCheckoutSession()
    {
        $token = $this->getJwtToken();
        $this->client->request('POST', '/api/stripe/checkout-session',[],[],[
            'CONTENT_TYPE' => 'application/json',
            'HTTP_AUTHORIZATION' => "Bearer ".$token
        ],json_encode([
            "offerId"=> 3
        ]));
        $content = json_decode($this->client->getResponse()->getContent(),true);
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertJson($this->client->getResponse()->getContent());
        $this->assertArrayHasKey("id", $content);
        $this->assertNotEmpty($content['id']);
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