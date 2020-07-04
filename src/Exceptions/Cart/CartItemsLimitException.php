<?php


namespace App\Exceptions\Cart;


use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class CartItemsLimitException extends \OverflowException implements HttpExceptionInterface
{
    public function getStatusCode()
    {
        return 400;
    }

    public function getHeaders()
    {
        return [];
    }
}