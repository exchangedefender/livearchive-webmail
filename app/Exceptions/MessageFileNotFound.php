<?php

namespace App\Exceptions;

use Exception;

class MessageFileNotFound extends Exception
{
    public static function for(string $attempted): self
    {
        return new self(message: "{$attempted} body was not found");
    }
}
