<?php

namespace Kayrunm\Qule\Tests\Unit;

use GuzzleHttp\Psr7\Response as GuzzleResponse;
use Kayrunm\Qule\Response;
use Kayrunm\Qule\Tests\TestCase;
use LogicException;

class ResponseTest extends TestCase
{
    /** @test */
    public function it_returns_original_response(): void
    {
        $original = new GuzzleResponse();
        $response = new Response($original);

        $this->assertSame($original, $response->getOriginalResponse());
    }

    /** @test */
    public function it_checks_given_key_is_set(): void
    {
        $original = new GuzzleResponse(200, [], '{"data":"foo"}');
        $response = new Response($original);

        $this->assertTrue(isset($response->data));
        $this->assertFalse(isset($response->operationName));
    }

    /** @test */
    public function it_returns_value_at_given_key(): void
    {
        $original = new GuzzleResponse(200, [], '{"data":"foo"}');
        $response = new Response($original);

        $this->assertEquals('foo', $response->data);
        $this->assertEquals(null, $response->operationName);
    }
}
