<?php

namespace Kayrunm\Qule\Exceptions;

class UnregisteredConnection extends QuleException
{
    public static function connection(string $connection): self
    {
        return new static("Connection `{$connection}` has not been registered");
    }
}
