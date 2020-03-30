<?php

namespace Kayrunm\Qule\Tests\Support\Stubs;

use Kayrunm\Qule\Query;

class QueryWithCustomResponseStub extends Query
{
    protected $file = 'query-from-file';

    protected $response = CustomResponseStub::class;
}
