<?php


namespace App\Exceptions\Cart;


use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ProductIsNotInCartException extends NotFoundHttpException
{

}