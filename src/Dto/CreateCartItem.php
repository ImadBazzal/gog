<?php


namespace App\Dto;


use Symfony\Component\Validator\Constraints as Assert;
use App\Services\Cart\CartService;

class CreateCartItem
{
    /**
     * @Assert\NotBlank
     * @Assert\Type(type="int")
     */
    public int $productId;
    /**
     * @Assert\NotBlank
     * @Assert\Type(type="int")
     * @Assert\Range(min="1", max=CartService::ITEM_QTY_LIMIT)
     */
    public int $qty;
}