<?php

namespace Kayrunm\Qule\Tests\Support\Stubs;

use Kayrunm\Qule\Query;

class QueryWithoutWrappingStub extends Query
{
    public $withoutWrapping = true;

    protected $file = 'query-from-file';
}
