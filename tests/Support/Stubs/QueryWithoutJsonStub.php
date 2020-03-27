<?php

namespace Kayrunm\Qule\Tests\Support\Stubs;

use Kayrunm\Qule\Query;

class QueryWithoutJsonStub extends Query
{
    protected $contentType = 'application/graphql';

    protected $file = 'query-from-file';
}
