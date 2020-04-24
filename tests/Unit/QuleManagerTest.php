<?php

namespace Kayrunm\Qule\Tests\Unit;

use GuzzleHttp\Client;
use Kayrunm\Qule\Qule;
use Kayrunm\Qule\Response;
use GuzzleHttp\HandlerStack;
use Kayrunm\Qule\QuleManager;
use Kayrunm\Qule\Tests\TestCase;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response as GuzzleResponse;
use Kayrunm\Qule\Exceptions\UnregisteredConnection;
use Kayrunm\Qule\Tests\Support\Stubs\InlineQueryStub;

class QuleManagerTest extends TestCase
{
    /** @test */
    public function it_registers_connections(): void
    {
        $manager = new QuleManager();
        $manager->register('default', new Client());
        $manager->register('secondary', new Client());

        $this->assertCount(2, $manager->getConnections());
    }

    /** @test */
    public function it_returns_connection_instances(): void
    {
        $manager = new QuleManager();
        $manager->register('default', new Client());

        $this->assertInstanceOf(Qule::class, $manager->on('default'));
    }

    /** @test */
    public function it_forwards_calls_to_default_connection(): void
    {
        $handler = new MockHandler();
        $handler->append(new GuzzleResponse(200, [], '{}'));
        $guzzle = new Client(['handler' => HandlerStack::create($handler)]);

        $manager = new QuleManager();
        $manager->register('default', $guzzle);

        $response = $manager->query(new InlineQueryStub());

        $this->assertInstanceOf(Response::class, $response);
    }

    /** @test */
    public function it_allows_connections_with_different_names(): void
    {
        $handler = new MockHandler();
        $handler->append(new GuzzleResponse(200, [], '{}'));
        $guzzle = new Client(['handler' => HandlerStack::create($handler)]);

        $manager = new QuleManager('', 'foo');
        $manager->register('foo', $guzzle);

        $response = $manager->query(new InlineQueryStub());

        $this->assertInstanceOf(Response::class, $response);
    }

    /** @test */
    public function it_throws_an_exception_for_unregistered_connections(): void
    {
        $this->expectException(UnregisteredConnection::class);

        $manager = new QuleManager();

        $this->assertInstanceOf(Qule::class, $manager->on('foo'));
    }
}
