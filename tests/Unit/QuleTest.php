<?php

namespace Kayrunm\Qule\Tests;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Kayrunm\Qule\Exceptions\QueryFileDoesntExist;
use Kayrunm\Qule\Qule;
use Kayrunm\Qule\Tests\Support\Stubs\InlineQueryStub;
use Kayrunm\Qule\Tests\Support\Stubs\QueryStub;
use Kayrunm\Qule\Tests\Support\Stubs\QueryWithoutWrappingStub;

class QuleTest extends TestCase
{
    /** @var \GuzzleHttp\Handler\MockHandler */
    private $handler;

    /** @var \GuzzleHttp\ClientInterface */
    private $guzzle;

    /** @var string */
    private $filepath;

    public function setUp(): void
    {
        $this->handler = new MockHandler();
        $this->guzzle = new Client([
            'handler' => HandlerStack::create($this->handler)
        ]);
        $this->filepath = dirname(__FILE__) . '/../Support/fixtures/';
    }

    /** @test */
    public function it_builds_the_request()
    {
        $this->handler->append(new Response());

        $qule = new Qule($this->guzzle, $this->filepath);
        $qule->query(QueryStub::class);

        $request = $this->handler->getLastRequest();

        $this->assertSame('GET', $request->getMethod());
        $this->assertSame('', (string) $request->getUri());
        $this->assertSame('application/graphql', $request->getHeaderLine('Content-Type'));
        $this->assertSame('{"query":"{}"}', $request->getBody()->getContents());
    }

    /** @test */
    public function it_passes_variables_to_the_request()
    {
        $this->handler->append(new Response());

        $qule = new Qule($this->guzzle, $this->filepath);
        $qule->query(QueryStub::class, [
            'foo' => 'bar'
        ]);

        $request = $this->handler->getLastRequest();
        $this->assertSame('{"query":"{}","variables":{"foo":"bar"}}', $request->getBody()->getContents());
    }

    /** @test */
    public function it_doesnt_wrap_a_query_when_without_wrapping_is_enabled()
    {
        $this->handler->append(new Response());

        $qule = new Qule($this->guzzle, $this->filepath);
        $qule->query(QueryWithoutWrappingStub::class);

        $request = $this->handler->getLastRequest();
        $this->assertSame('{}', $request->getBody()->getContents());
    }

    /** @test */
    public function it_loads_a_query_inline()
    {
        $this->handler->append(new Response());

        $path = dirname(__FILE__) . '/../Support/fixtures/';

        $qule = new Qule($this->guzzle);
        $qule->query(InlineQueryStub::class);

        $request = $this->handler->getLastRequest();
        $this->assertSame('{"query":"{}"}', $request->getBody()->getContents());
    }

    /** @test */
    public function it_throws_an_exception_for_a_file_that_doesnt_exist()
    {
        $this->expectException(QueryFileDoesntExist::class);

        $qule = new Qule($this->guzzle);
        $qule->query(QueryStub::class);
    }
}
