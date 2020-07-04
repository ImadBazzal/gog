<?php


namespace App\DataTransformer;


use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use App\Dto\CartItemOutput;
use App\Dto\CartOutput;
use App\Entity\Cart;
use App\Entity\CartItem;
use App\Services\Price\Denormalizer as PriceDenormalizer;

class CartOutputDataTransformer implements DataTransformerInterface
{
    private PriceDenormalizer $priceDenormalizer;

    public function __construct(PriceDenormalizer $priceDenormalizer)
    {
        $this->priceDenormalizer = $priceDenormalizer;
    }

    /**
     * @param Cart $object
     * @param string $to
     * @param array $context
     * @return CartOutput
     */
    public function transform($object, string $to, array $context = [])
    {
        $cart = new CartOutput();

        $cart->id = $object->getId();

        $cart->items = array_map(function (CartItem $item) {
            $itemOutput = new CartItemOutput();

            $itemOutput->title = $item->getProduct()->getTitle();
            $itemOutput->price = $this->priceDenormalizer->denormalize($item->getProduct()->getPrice());
            $itemOutput->qty   = $item->getQuantity();

            return $itemOutput;
        }, $object->getCartItems()->getValues());

        /** @var float $totalPrice */
        $totalPrice = array_reduce($cart->items, function (float $totalPrice, CartItemOutput $item) {
            $totalPrice += $item->price * $item->qty;

            return $totalPrice;
        }, .0);

        $cart->totalPrice = (string)$totalPrice;

        return $cart;
    }

    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        return CartOutput::class === $to && $data instanceof Cart;
    }
}