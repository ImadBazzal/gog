<?php


namespace App\Services\Price;


class Normalizer
{
    public function normalize(float $price): int
    {
        return (int)($price * 100);
    }
}