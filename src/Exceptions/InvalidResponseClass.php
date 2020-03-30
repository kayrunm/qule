<?php

namespace Kayrunm\Qule\Exceptions;

class InvalidResponseClass extends QuleException
{
    public static function make(string $class): self
    {
        return new static("Response class doesn't extend base Response [{$class}]");
    }
}
