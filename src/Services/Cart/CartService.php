<?php


namespace App\Services\Cart;


use App\Entity\Cart;
use App\Entity\CartItem;
use App\Entity\User;
use App\Exceptions\Cart\CartItemsLimitException;
use App\Exceptions\Cart\ProductIsNotInCartException;
use App\Exceptions\ProductNotFoundException;
use App\Repository\CartRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class CartService
{
    private const CART_ITEMS_LIMIT = 3;
    public const ITEM_QTY_LIMIT = 10;

    private CartRepository $cartRepository;

    private EntityManagerInterface $entityManager;

    private ProductRepository $productRepository;

    public function __construct(EntityManagerInterface $entityManager, ProductRepository $productRepository, CartRepository $cartRepository)
    {
        $this->entityManager     = $entityManager;
        $this->cartRepository    = $cartRepository;
        $this->productRepository = $productRepository;
    }

    /**
     * @param UserInterface|User $user
     * @return Cart
     */
    public function createCart($user): Cart
    {
        $cart = new Cart();

        $cart->setUser($user);

        $this->entityManager->persist($cart);

        $this->entityManager->flush();

        return $cart;
    }

    public function addItem(Cart $cart, int $productId, int $qty): void
    {
        if ($cart->getCartItems()->count() >= self::CART_ITEMS_LIMIT) {
           throw new CartItemsLimitException(sprintf('Maximum "%d" items allowed', self::CART_ITEMS_LIMIT));
        }

        $product = $this->productRepository->find($productId);

        if (!$product) {
            throw new ProductNotFoundException(sprintf('Product: "%d" not found', $productId));
        }

        try {
            $this->removeItem($cart, $productId);
        } catch (ProductIsNotInCartException $e) {
        }

        $cartItem = (new CartItem())
            ->setProduct($product)
            ->setQuantity($qty)
            ->setCart($cart);

        $this->entityManager->persist($cartItem);

        $this->entityManager->flush();
    }

    public function removeItem(Cart $cart, int $productId): void
    {
        $cartItems = $cart->getCartItems()->filter(function (CartItem $cartItem) use ($productId) {
            return $productId === $cartItem->getProduct()->getId();
        });

        if (!$cartItems->isEmpty()) {
            $cart->removeCartItem($cartItems->first());
        } else {
            throw new ProductIsNotInCartException(sprintf('Product: "%d" is not in the cart', $productId));
        }
    }


}