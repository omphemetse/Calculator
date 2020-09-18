<?php

namespace App\Exceptions;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

trait ExceptionTrait
{
    public function apiException($request, $e)
    {
        if ($this->isNotFoundHttp($e)) {
            return $this->CustomHttpResponseException($e);
        }

        if ($this->isMethodNotAllowed($e)) {
            return $this->CustomMethodResponseException($e);
        }

        return parent::render($request, $e);
    }

    protected function isNotFoundHttp($e)
    {
        return $e instanceof NotFoundHttpException;
    }

    protected function isMethodNotAllowed($e)
    {
        return $e instanceof MethodNotAllowedHttpException;
    }

    protected function CustomHttpResponseException($e)
    {
        return response()->json([
            'errors' => 'Requested path is incorrect, please check if you are using the correct path.'
        ], Response::HTTP_NOT_FOUND);
    }

    protected function CustomMethodResponseException($e)
    {
        return response()->json([
            'errors' => 'The method received in the request-line is known by the origin server but not supported by the target resource. Please check if you have included the target resource in the request-line.'
        ], Response::HTTP_METHOD_NOT_ALLOWED);
    }
}
