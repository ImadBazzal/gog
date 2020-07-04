<?php


namespace Tests;


use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\Client;
use App\Entity\User;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;
use Symfony\Component\Security\Core\User\UserInterface;

class ProductTest extends ApiTestCase
{
    use RefreshDatabaseTrait;

    private Client $client;
    private UserInterface $admin;
    private UserInterface $user;

    public function setUp()
    {
        $this->client = static::createClient();

        $this->client->setDefaultOptions([
            'headers' => [
                'content-type' => 'application/json',
                'accept'       => 'application/json',
            ],
        ]);

        $this->admin = $this->createUser('admin@admin.com', 'admin', [User::ROLE_ADMIN]);
        $this->user  = $this->createUser('client@client.com', 'client', [User::ROLE_USER]);
    }

    public function testListAllProducts()
    {
        $token = $this->getToken($this->user);

        $this->client->request(
            'GET', '/api/products',
            [
                'headers' => [
                    "Authorization" => ["Bearer {$token}"]
                ],
            ],
        );

        $this->assertJsonEquals([
            [
                'id'    => 1,
                'title' => 'Fallout',
                'price' => 1.99,
            ],
            [
                'id'    => 2,
                'title' => 'Don’t Starve',
                'price' => 2.99,
            ],
            [
                'id'    => 3,
                'title' => 'Baldur’s Gate',
                'price' => 3.99,
            ]
        ]);

        $this->assertResponseIsSuccessful();
    }

    public function testListAllProductsPagination()
    {
        $token = $this->getToken($this->user);

        $this->client->request(
            'GET', '/api/products',
            [
                'query'   => [
                    'page' => 2
                ],
                'headers' => [
                    "Authorization" => ["Bearer {$token}"]
                ],
            ],
        );

        $this->assertResponseIsSuccessful();

        $this->assertJsonEquals([
            [
                'id'    => 4,
                'title' => 'Icewind Dale',
                'price' => 4.99,
            ],
            [
                'id'    => 5,
                'title' => 'Bloodborne',
                'price' => 5.99,
            ]
        ]);
    }

    public function testUpdate()
    {
        $this->client->request(
            'PUT', '/api/products/1',
            [
                'headers' => [
                    "Authorization" => ["Bearer {$this->getToken($this->admin)}"]
                ],
                'json'    => [
                    'title' => 'Fallout 2',
                    'price' => 2.99
                ]
            ],
        );

        $this->assertJsonEquals([
            'id'    => 1,
            'title' => 'Fallout 2',
            'price' => 2.99
        ]);
    }

    public function testPartialUpdate()
    {
        $token = $this->getToken($this->admin);

        $this->client->request(
            'PUT', '/api/products/1',
            [
                'headers' => [
                    "Authorization" => ["Bearer {$token}"]
                ],
                'json'    => [
                    'title' => 'Fallout 3',
                ]
            ],
        );

        $this->assertJsonEquals([
            'id'    => 1,
            'title' => 'Fallout 3',
            'price' => 1.99
        ]);
    }

    public function testDelete()
    {
        $token = $this->getToken($this->admin);

        $this->client->request(
            'DELETE', '/api/products/1',
            [
                'headers' => [
                    "Authorization" => ["Bearer {$token}"]
                ]
            ],
        );

        $this->assertResponseIsSuccessful();
    }

    public function testCreate()
    {
        $this->client->request(
            'POST', '/api/products',
            [
                'headers' => [
                    "Authorization" => ["Bearer {$this->getToken($this->admin)}"]
                ],
                'json'    => [
                    'title' => 'Fallout 2',
                    'price' => 2.99
                ]
            ],
        );

        $this->assertJsonEquals([
            'id'    => 6,
            'title' => 'Fallout 2',
            'price' => 2.99
        ]);
    }

    public function testCreateInvalidDataException()
    {
        $this->client->request(
            'POST', '/api/products',
            [
                'headers' => [
                    "Authorization" => ["Bearer {$this->getToken($this->admin)}"]
                ],
                'json'    => [
                    'title' => 'Fallout 2',
                    'price' => 'string price'
                ]
            ],
        );

        $this->assertResponseStatusCodeSame(400);
    }

    public function testCreateDuplicateException()
    {
        $this->client->request(
            'POST', '/api/products',
            [
                'headers' => [
                    "Authorization" => ["Bearer {$this->getToken($this->admin)}"]
                ],
                'json'    => [
                    'title' => 'Fallout',
                    'price' => 1.99
                ]
            ],
        );

        $this->assertResponseStatusCodeSame(400);
    }
}