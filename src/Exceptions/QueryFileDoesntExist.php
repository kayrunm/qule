<?php

namespace Kayrunm\Qule\Exceptions;

class QueryFileDoesntExist extends QuleException
{
    public static function file(string $file): self
    {
        return new static("Query file doesn't exist for query `{$file}`");
    }
}
