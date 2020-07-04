<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\CartItemRepository;
use App\Dto\CreateCartItem;
use App\Dto\DeleteCartItem;
use App\Dto\CartOutput;
use App\Controller\Cart\AddItem;
use App\Controller\Cart\RemoveItem;


use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CartItemRepository::class)
 * @ApiResource(
 *     itemOperations={
 *          "get",
 *          "delete"={
 *              "controller"=RemoveItem::class,
 *              "path"="carts/{id}/{productId}"
 *          },
 *     },
 *     collectionOperations={
 *          "post"={
 *              "controller"=AddItem::class,
 *              "path"="carts/{id}",
 *              "input"=CreateCartItem::class,
 *              "output"=CartOutput::class
 *          },
 *     },
 *     subresourceOperations={},
 * )
 */
class CartItem
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="smallint")
     */
    private $quantity;

    /**
     * @ORM\ManyToOne(targetEntity=Product::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $product;

    /**
     * @ORM\ManyToOne(targetEntity=Cart::class, inversedBy="cartItems")
     * @ORM\JoinColumn(nullable=false)
     */
    private $cart;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): self
    {
        $this->product = $product;

        return $this;
    }

    public function getCart(): ?Cart
    {
        return $this->cart;
    }

    public function setCart(?Cart $cart): self
    {
        $this->cart = $cart;

        return $this;
    }
}
