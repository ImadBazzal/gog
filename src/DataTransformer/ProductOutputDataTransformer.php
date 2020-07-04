<?php

namespace App\DataTransformer;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use App\Dto\ProductOutput;
use App\Entity\Product;
use App\Services\Price\Denormalizer as PriceDenormalizer;

class ProductOutputDataTransformer implements DataTransformerInterface
{
    /**
     * @var PriceDenormalizer
     */
    private PriceDenormalizer $priceDenormalizer;

    public function __construct(PriceDenormalizer $priceDenormalizer)
    {
        $this->priceDenormalizer = $priceDenormalizer;
    }

    public function transform($object, string $to, array $context = [])
    {
        $product = new ProductOutput();

        $product->id = $object->getId();
        $product->title = $object->getTitle();
        $product->price = $this->priceDenormalizer->denormalize($object->getPrice());

        return $product;
    }

    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        return ProductOutput::class === $to && $data instanceof Product;
    }
}