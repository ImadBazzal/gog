<?php

namespace App\DataTransformer;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use ApiPlatform\Core\Validator\ValidatorInterface;
use App\Dto\CreateProduct;
use App\Entity\Product;
use App\Services\Price\Normalizer as PriceNormalizer;

class ProductInputDataTransformer implements DataTransformerInterface
{
    private ValidatorInterface $validator;
    private PriceNormalizer $priceNormalizer;

    public function __construct(ValidatorInterface $validator, PriceNormalizer $priceNormalizer)
    {
        $this->validator       = $validator;
        $this->priceNormalizer = $priceNormalizer;
    }

    /**
     * @param CreateProduct $object
     * @param string $to
     * @param array $context
     * @return Product|object
     */
    public function transform($object, string $to, array $context = [])
    {
        $this->validator->validate($object);

        return (new Product())
            ->setPrice($this->priceNormalizer->normalize($object->price))
            ->setTitle($object->title);
    }

    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        if ($data instanceof Product) {
            return false;
        }

        return $to === Product::class && $context['input']['class'] === CreateProduct::class;
    }
}