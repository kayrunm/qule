<?php

namespace Kayrunm\Qule\Tests\Support\Stubs;

use Kayrunm\Qule\Query;

class QueryWithInvalidCustomResponseStub extends Query
{
    protected $file = 'query-from-file';

    protected $response = InvalidCustomResponse::class;
}

class InvalidCustomResponse
{
}
