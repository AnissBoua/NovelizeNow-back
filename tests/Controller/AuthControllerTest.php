<?php 

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;

class AuthControllerTest extends WebTestCase {
    private $client;
    protected $databaseTool;
    protected $data;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->databaseTool = static::getContainer()->get(DatabaseToolCollection::class)->get();
        $this->data = $this->databaseTool->loadAliceFixture([
            __DIR__. './fixtures/auth.yaml',
        ]);
    }

    public function testPostRegistration(){
        $this->client->request('POST', '/api/registration', [
            'name' => 'test',
            'lastname' => 'test',
            'email' => 'test@hotmail.com',
            'password' => 'password',
        ], [], [
            'CONTENT_TYPE' => 'multipart/form-data',
        ]);
        $this->assertEquals(201, $this->client->getResponse()->getStatusCode());
        $this->assertJson($this->client->getResponse()->getContent());
    }

    public function testPostRegistrationWithOptional(){
        $avatar = new UploadedFile('public\uploads\test\20230518101205-A8w4O3p6bC9yQ2nA-zelda-breath-of-the-wild.png', '20230518101205-A8w4O3p6bC9yQ2nA-zelda-breath-of-the-wild.png');
        $this->client->request('POST', '/api/registration', [
            'name' => 'test',
            'lastname' => 'test',
            'username' => 'test',
            'email' => 'test@hotmail.com',
            'password' => 'password',
        ], [
            'avatar' => $avatar,
        ], [
            'CONTENT_TYPE' => 'multipart/form-data',
        ]);
        $this->assertEquals(201, $this->client->getResponse()->getStatusCode());
        $this->assertJson($this->client->getResponse()->getContent());
    }

    public function testPostRegistrationMissingRequired(){
        $avatar = new UploadedFile('public\uploads\test\20230518101205-A8w4O3p6bC9yQ2nA-zelda-breath-of-the-wild.png', '20230518101205-A8w4O3p6bC9yQ2nA-zelda-breath-of-the-wild.png');
        $this->client->request('POST', '/api/registration', [
            'username' => 'test',
        ], [
            'avatar' => $avatar,
        ], [
            'CONTENT_TYPE' => 'multipart/form-data',
        ]);
        $this->assertEquals(400, $this->client->getResponse()->getStatusCode());
        $this->assertJson($this->client->getResponse()->getContent());
    }

    public function testPostLogin(){
        $this->client->request('POST', '/api/login', [], [], [
            'CONTENT_TYPE' => 'application/json',
        ], json_encode([
            'email' => 'user2@gmail.com',
            'password' => 'password',
        ]));
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertJson($this->client->getResponse()->getContent());
        $this->assertArrayHasKey('token', json_decode($this->client->getResponse()->getContent(), true));
        $this->assertArrayHasKey('refresh_token', json_decode($this->client->getResponse()->getContent(), true));
    }

    public function testPostLoginNoBody(){
        $this->client->request('POST', '/api/login', [], [], [
            'CONTENT_TYPE' => 'application/json',
        ]);
        $this->assertEquals(400, $this->client->getResponse()->getStatusCode());
        $this->assertJson($this->client->getResponse()->getContent());
    }

    public function testGetMe(){
        $token = $this->getJwtToken();
        $this->client->request('GET', '/api/me', [], [], [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_AUTHORIZATION' => 'Bearer ' . $token,
        ]);
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertJson($this->client->getResponse()->getContent());
        $this->assertArrayHasKey('id', json_decode($this->client->getResponse()->getContent(), true));
        $this->assertArrayHasKey('name', json_decode($this->client->getResponse()->getContent(), true));
        $this->assertArrayHasKey('lastname', json_decode($this->client->getResponse()->getContent(), true));
        $this->assertArrayHasKey('email', json_decode($this->client->getResponse()->getContent(), true));
        $this->assertArrayHasKey('username', json_decode($this->client->getResponse()->getContent(), true));
        $this->assertArrayHasKey('roles', json_decode($this->client->getResponse()->getContent(), true));
        $this->assertArrayNotHasKey('password', json_decode($this->client->getResponse()->getContent(), true));
    }

    public function testGetMeUnauthorized(){
        $this->client->request('GET', '/api/me', [], [], [
            'CONTENT_TYPE' => 'application/json',
        ]);
        $this->assertEquals(401, $this->client->getResponse()->getStatusCode());
        $this->assertJson($this->client->getResponse()->getContent());
    }

    public function testGetMeCoins(){
        $token = $this->getJwtToken();
        $this->client->request('GET', '/api/user/coins', [], [], [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_AUTHORIZATION' => 'Bearer ' . $token,
        ]);
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertJson($this->client->getResponse()->getContent());
        // 100
        $this->assertArrayHasKey('coins', json_decode($this->client->getResponse()->getContent(), true));
        $this->assertEquals(100, json_decode($this->client->getResponse()->getContent(), true)['coins']);
    }

    public function testGetMeCoinsUnauthorized(){
        $this->client->request('GET', '/api/user/coins', [], [], [
            'CONTENT_TYPE' => 'application/json',
        ]);
        $this->assertEquals(401, $this->client->getResponse()->getStatusCode());
        $this->assertJson($this->client->getResponse()->getContent());
    }

    public function testGetMeAvatar(){
        $token = $this->getJwtToken();
        $this->client->request('GET', '/api/user/avatar', [], [], [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_AUTHORIZATION' => 'Bearer ' . $token,
        ]);
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertJson($this->client->getResponse()->getContent());
        $this->assertArrayHasKey('avatar', json_decode($this->client->getResponse()->getContent(), true));
    }

    public function testGetMeAvatarUnauthorized(){
        $this->client->request('GET', '/api/user/avatar', [], [], [
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
            'email' => 'user2@gmail.com',
            'password' => 'password',
        ]));

        $response = json_decode($this->client->getResponse()->getContent());
        return $response->token; 
    }
}