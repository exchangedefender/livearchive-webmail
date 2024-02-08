<?php

namespace App\Exceptions;

class InvalidEmailAddress extends \Exception
{
    public static function for(string $attempted): self
    {
        return new self(message: "{$attempted} is not a valid address");
    }
}
