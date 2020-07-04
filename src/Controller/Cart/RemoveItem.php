<?php


namespace App\Controller\Cart;


use App\Dto\DeleteCartItem;
use App\Dto\CreateCartItem;
use App\Entity\Cart;
use App\Entity\CartItem;
use App\Services\Cart\CartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RemoveItem extends AbstractController
{
    /**
     * @var CartService
     */
    private CartService $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function __invoke(Cart $cart, int $productId): Cart
    {
        $this->cartService->removeItem($cart, $productId);

        return $cart;
    }
}