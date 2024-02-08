<?php

namespace App\Exceptions;

class InvalidConfigurationOptionException extends \Exception
{
    public static function for(string $option, string $choice)
    {
        return new self(message: "\"{$choice}\" is not a valid value for {$option}");
    }
}
