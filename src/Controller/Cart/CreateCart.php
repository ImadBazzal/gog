<?php


namespace App\Controller\Cart;


use App\Entity\Cart;
use App\Services\Cart\CartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CreateCart extends AbstractController
{
    /**
     * @var CartService
     */
    private CartService $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function __invoke(): Cart
    {
        return $this->cartService->createCart($this->getUser());
    }
}