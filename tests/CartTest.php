<?php


namespace Tests;


use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\Client;
use App\Entity\User;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;
use Symfony\Component\Security\Core\User\UserInterface;

class CartTest extends ApiTestCase
{
    use RefreshDatabaseTrait;

    private Client $client;
    private UserInterface $user;

    public function setUp()
    {
        $this->bootKernel();

        $this->client = static::createClient();

        $this->client->disableReboot();

        $this->client->setDefaultOptions([
            'headers' => [
                'content-type' => 'application/json',
                'accept'       => 'application/json',
            ],
        ]);

        $this->user = $this->createUser('client@client.com', 'client', [User::ROLE_USER]);
    }

    private function createCart()
    {
        $token = $this->getToken($this->user);

        $this->client->request(
            'POST', '/api/carts',
            [
                'headers' => [
                    "Authorization" => ["Bearer {$token}"]
                ],
                'json'    => []
            ],
        );

        $this->assertResponseIsSuccessful();
    }

    public function testCreateCart()
    {
        $this->createCart();
    }

    public function testGetCart()
    {
        $this->createCart();

        $this->client->request(
            'GET', '/api/carts/1',
            [
                'headers' => [
                    "Authorization" => ["Bearer {$this->getToken($this->user)}"]
                ],
            ],
        );

        $this->assertResponseIsSuccessful();
    }

    public function testAddProduct()
    {
        $this->createCart();

        $this->client->request(
            'POST', '/api/carts/1',
            [
                'headers' => [
                    "Authorization" => ["Bearer {$this->getToken($this->user)}"]
                ],
                'json'    => [
                    'productId' => 1,
                    'qty'       => 3
                ]
            ],
        );

        $this->assertJsonEquals([
            'id'         => 1,
            'items'      =>
                [
                    [
                        'title' => 'Fallout',
                        'price' => 1.99,
                        'qty'   => 3,
                    ],
                ],
            'totalPrice' => 5.97
        ]);
    }

    public function testAppendProduct()
    {
        $this->createCart();

        $this->client->request(
            'POST', '/api/carts/1',
            [
                'headers' => [
                    "Authorization" => ["Bearer {$this->getToken($this->user)}"]
                ],
                'json'    => [
                    'productId' => 1,
                    'qty'       => 3
                ]
            ],
        );

        $this->client->request(
            'POST', '/api/carts/1',
            [
                'headers' => [
                    "Authorization" => ["Bearer {$this->getToken($this->user)}"]
                ],
                'json'    => [
                    'productId' => 2,
                    'qty'       => 1
                ]
            ],
        );

        $this->assertJsonEquals([
            'id'         => 1,
            'items'      =>
                [
                    [
                        'title' => 'Fallout',
                        'price' => 1.99,
                        'qty'   => 3,
                    ],
                    [
                        'title' => 'Don’t Starve',
                        'price' => 2.99,
                        'qty'   => 1,
                    ],
                ],
            'totalPrice' => 8.96
        ]);
    }

    public function testAppendSameProduct()
    {
        $this->createCart();

        $this->client->request(
            'POST', '/api/carts/1',
            [
                'headers' => [
                    "Authorization" => ["Bearer {$this->getToken($this->user)}"]
                ],
                'json'    => [
                    'productId' => 1,
                    'qty'       => 3
                ]
            ],
        );

        $this->client->request(
            'POST', '/api/carts/1',
            [
                'headers' => [
                    "Authorization" => ["Bearer {$this->getToken($this->user)}"]
                ],
                'json'    => [
                    'productId' => 1,
                    'qty'       => 5
                ]
            ],
        );

        $this->assertJsonEquals([
            'id'         => 1,
            'items'      =>
                [
                    [
                        'title' => 'Fallout',
                        'price' => 1.99,
                        'qty'   => 5,
                    ],

                ],
            'totalPrice' => 9.95
        ]);
    }

    public function testRemoveFromCart()
    {
        $this->createCart();

        $this->client->request(
            'POST', '/api/carts/1',
            [
                'headers' => [
                    "Authorization" => ["Bearer {$this->getToken($this->user)}"]
                ],
                'json'    => [
                    'productId' => 1,
                    'qty'       => 3
                ]
            ],
        );

        $this->client->request(
            'POST', '/api/carts/1',
            [
                'headers' => [
                    "Authorization" => ["Bearer {$this->getToken($this->user)}"]
                ],
                'json'    => [
                    'productId' => 2,
                    'qty'       => 3
                ]
            ],
        );

        $this->client->request(
            'DELETE', '/api/carts/1/1',
            [
                'headers' => [
                    "Authorization" => ["Bearer {$this->getToken($this->user)}"]
                ],
            ],
        );

        $this->assertResponseIsSuccessful();

        $this->assertResponseStatusCodeSame(204);

        $this->client->request(
            'GET', '/api/carts/1',
            [
                'headers' => [
                    "Authorization" => ["Bearer {$this->getToken($this->user)}"]
                ],
            ],
        );

    }

    public function testGetCartNotFoundException()
    {
        $this->createCart();

        $this->client->request(
            'GET', '/api/carts/2',
            [
                'headers' => [
                    "Authorization" => ["Bearer {$this->getToken($this->user)}"]
                ],
            ],
        );

        $this->assertResponseStatusCodeSame(404);
    }

    public function testProductQtytLimitException()
    {
        $this->createCart();

        $this->client->request(
            'POST', '/api/carts/1',
            [
                'headers' => [
                    "Authorization" => ["Bearer {$this->getToken($this->user)}"]
                ],
                'json'    => [
                    'productId' => 1,
                    'qty'       => 11
                ]
            ],
        );

        $this->assertResponseStatusCodeSame(400);
    }

    public function testProductCartLimitException()
    {
        $this->createCart();

        $this->client->request(
            'POST', '/api/carts/1',
            [
                'headers' => [
                    "Authorization" => ["Bearer {$this->getToken($this->user)}"]
                ],
                'json'    => [
                    'productId' => 1,
                    'qty'       => 1
                ]
            ],
        );

        $this->client->request(
            'POST', '/api/carts/1',
            [
                'headers' => [
                    "Authorization" => ["Bearer {$this->getToken($this->user)}"]
                ],
                'json'    => [
                    'productId' => 2,
                    'qty'       => 1
                ]
            ],
        );

        $this->client->request(
            'POST', '/api/carts/1',
            [
                'headers' => [
                    "Authorization" => ["Bearer {$this->getToken($this->user)}"]
                ],
                'json'    => [
                    'productId' => 3,
                    'qty'       => 1
                ]
            ],
        );

        $this->client->request(
            'POST', '/api/carts/1',
            [
                'headers' => [
                    "Authorization" => ["Bearer {$this->getToken($this->user)}"]
                ],
                'json'    => [
                    'productId' => 4,
                    'qty'       => 1
                ]
            ],
        );

        $this->assertResponseStatusCodeSame(400);
    }
}