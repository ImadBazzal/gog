<?php


namespace App\Controller\Cart;


use App\Dto\CreateCartItem;
use App\Entity\Cart;
use App\Services\Cart\CartService;

class AddItem
{
    /**
     * @var CartService
     */
    private CartService $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function __invoke(Cart $cart, CreateCartItem $data): Cart
    {
        $this->cartService->addItem($cart, $data->productId, $data->qty);

        return $cart;
    }
}