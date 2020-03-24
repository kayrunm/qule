<?php

namespace Kayrunm\Qule\Tests\Support\Stubs;

use Kayrunm\Qule\Contracts\InlineQuery;
use Kayrunm\Qule\Query;

class InlineQueryStub extends Query implements InlineQuery
{
    public function getQuery(): string
    {
        return '{}';
    }
}
