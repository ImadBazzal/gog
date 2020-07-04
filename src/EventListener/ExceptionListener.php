<?php


namespace App\EventListener;


use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\Serializer\Exception\NotNormalizableValueException;

class ExceptionListener
{
    public function onKernelException(ExceptionEvent $event)
    {
        $exception = $event->getThrowable();

        if ($exception instanceof HttpExceptionInterface) {
            $response = new Response();
            $response->setContent($exception->getMessage());
            $response->setStatusCode($exception->getStatusCode());
            $response->headers->replace($exception->getHeaders());

            $event->setResponse($response);
        } elseif ($exception instanceof NotNormalizableValueException) {
            $response = new Response();
            $response->setContent($exception->getMessage());
            $response->setStatusCode(400);

            $event->setResponse($response);
        }
    }
}