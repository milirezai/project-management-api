<?php

namespace App\Exceptions;

class CreateCompanyException extends BusinessExceptions
{
    public function __construct(string $message = "You don't have the freedom to start a new company.", int $code = 409)
    {
        parent::__construct($message, $code);
    }
}
