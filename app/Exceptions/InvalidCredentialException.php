<?php

namespace App\Exceptions;

use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

class InvalidCredentialException extends HttpException
{
    public function __construct(
        int $statusCode = 401,
        ?string $message = 'Authentication Failed',
        Throwable $previous = null,
        array $headers = [],
        ?int $code = 0
    ) {
        parent::__construct($statusCode, $message, $previous, $headers, $code);
    }
}
