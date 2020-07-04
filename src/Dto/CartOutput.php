<?php


namespace App\Dto;


class CartOutput
{
    public int $id;
    public array $items;
    public float $totalPrice;
}