<?php

namespace App\DataTransformer;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use ApiPlatform\Core\Serializer\AbstractItemNormalizer;
use ApiPlatform\Core\Validator\ValidatorInterface;
use App\Dto\UpdateProduct;
use App\Entity\Product;
use App\Services\Price\Normalizer as PriceNormalizer;

class ProductUpdateDataTransformer implements DataTransformerInterface
{
    private ValidatorInterface $validator;
    private PriceNormalizer $priceNormalizer;

    public function __construct(ValidatorInterface $validator, PriceNormalizer $priceNormalizer)
    {
        $this->validator       = $validator;
        $this->priceNormalizer = $priceNormalizer;
    }

    /**
     * @param UpdateProduct $object
     * @param string $to
     * @param array $context
     * @return Product|object
     */
    public function transform($object, string $to, array $context = [])
    {
        $this->validator->validate($object);

        $product = $context[AbstractItemNormalizer::OBJECT_TO_POPULATE];

        if ($object->price !== null) {
            $product->setPrice($this->priceNormalizer->normalize($object->price));
        }

        if ($object->title !== null) {
            $product->setTitle($object->title);
        }

        return $product;
    }

    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        if ($data instanceof Product) {
            return false;
        }

        return $to === Product::class && $context['input']['class'] === UpdateProduct::class;
    }
}