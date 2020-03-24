<?php

namespace Kayrunm\Qule\Contracts;

interface InlineQuery
{
    /**
     * The query string to send to the API endpoint.
     *
     * @return string
     */
    public function getQuery(): string;
}
