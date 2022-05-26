<?php

namespace App\Exceptions;

use Illuminate\Contracts\Support\MessageBag;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

class ValidatorFailedException extends HttpException
{
    public MessageBag $errors;

    public function __construct(
        string $message,
        MessageBag $errors,
        int $statusCode = 400,
        ?Throwable $previous = null,
        array $headers = [],
        int $code = 0
    ) {
        parent::__construct($statusCode, $message, $previous, $headers, $code);

        $this->errors = $errors;
    }
}
