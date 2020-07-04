<?php


namespace App\Exceptions;


use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ProductNotFoundException extends NotFoundHttpException
{
    public function getStatusCode()
    {
        return Response::HTTP_NOT_FOUND;
    }
}