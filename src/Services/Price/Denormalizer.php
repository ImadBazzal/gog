<?php


namespace App\Services\Price;


class Denormalizer
{
    public function denormalize(int $price): float
    {
        return round($price / 100, 2);
    }
}