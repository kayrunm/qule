<?php

namespace Kayrunm\Qule\Tests\Unit;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response as GuzzleResponse;
use Kayrunm\Qule\Exceptions\InvalidResponseClass;
use Kayrunm\Qule\Exceptions\QueryFileDoesntExist;
use Kayrunm\Qule\Qule;
use Kayrunm\Qule\Response;
use Kayrunm\Qule\Tests\Support\Stubs\CustomResponseStub;
use Kayrunm\Qule\Tests\Support\Stubs\InlineQueryStub;
use Kayrunm\Qule\Tests\Support\Stubs\QueryStub;
use Kayrunm\Qule\Tests\Support\Stubs\QueryWithCustomResponseStub;
use Kayrunm\Qule\Tests\Support\Stubs\QueryWithInvalidCustomResponseStub;
use Kayrunm\Qule\Tests\Support\Stubs\QueryWithoutJsonStub;
use Kayrunm\Qule\Tests\TestCase;

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
    public function it_builds_the_request(): void
    {
        $this->handler->append(new GuzzleResponse());

        $qule = new Qule($this->guzzle, $this->filepath);
        $qule->query(new QueryStub());

        $request = $this->handler->getLastRequest();

        $this->assertSame('GET', $request->getMethod());
        $this->assertSame('', (string) $request->getUri());
        $this->assertSame('application/json', $request->getHeaderLine('Content-Type'));
        $this->assertSame('{"query":"{}\n"}', $request->getBody()->getContents());
    }

    /** @test */
    public function it_passes_variables_to_the_request(): void
    {
        $this->handler->append(new GuzzleResponse());

        $qule = new Qule($this->guzzle, $this->filepath);
        $qule->query(new QueryStub(), [
            'foo' => 'bar'
        ]);

        $request = $this->handler->getLastRequest();
        $this->assertSame('{"query":"{}\n","variables":{"foo":"bar"}}', $request->getBody()->getContents());
    }

    /** @test */
    public function it_doesnt_wrap_a_query_when_content_type_isnt_json(): void
    {
        $this->handler->append(new GuzzleResponse());

        $qule = new Qule($this->guzzle, $this->filepath);
        $qule->query(new QueryWithoutJsonStub());

        $request = $this->handler->getLastRequest();
        $this->assertSame("{}\n", $request->getBody()->getContents());
    }

    /** @test */
    public function it_loads_a_query_inline(): void
    {
        $this->handler->append(new GuzzleResponse());

        $path = dirname(__FILE__) . '/../Support/fixtures/';

        $qule = new Qule($this->guzzle);
        $qule->query(new InlineQueryStub());

        $request = $this->handler->getLastRequest();
        $this->assertSame('{"query":"{}"}', $request->getBody()->getContents());
    }

    /** @test */
    public function it_throws_an_exception_for_a_file_that_doesnt_exist(): void
    {
        $this->expectException(QueryFileDoesntExist::class);

        $qule = new Qule($this->guzzle);
        $qule->query(new QueryStub());
    }

    /** @test */
    public function it_returns_a_response_object(): void
    {
        $this->handler->append(new GuzzleResponse());

        $qule = new Qule($this->guzzle, $this->filepath);
        $response = $qule->query(new QueryStub());

        $this->assertInstanceOf(Response::class, $response);
    }

    /** @test */
    public function it_returns_a_custom_response_object(): void
    {
        $this->handler->append(new GuzzleResponse());

        $qule = new Qule($this->guzzle, $this->filepath);
        $response = $qule->query(new QueryWithCustomResponseStub());

        $this->assertInstanceOf(CustomResponseStub::class, $response);
    }

    /** @test */
    public function it_throws_exception_for_invalid_custom_response(): void
    {
        $this->expectException(InvalidResponseClass::class);

        $this->handler->append(new GuzzleResponse());

        $qule = new Qule($this->guzzle, $this->filepath);

        $qule->query(new QueryWithInvalidCustomResponseStub());
    }
}
