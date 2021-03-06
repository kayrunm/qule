<?php

namespace Kayrunm\Qule\Tests\Unit;

use GuzzleHttp\Psr7\Response as GuzzleResponse;
use Kayrunm\Qule\Response;
use Kayrunm\Qule\Tests\TestCase;

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
    public function it_casts_to_an_array(): void
    {
        $original = new GuzzleResponse(200, [], '{"data":"foo"}');
        $response = new Response($original);

        $this->assertEquals(['data' => 'foo'], $response->toArray());
    }

    /** @test */
    public function it_casts_to_an_object(): void
    {
        $original = new GuzzleResponse(200, [], '{"data":"foo"}');
        $response = new Response($original);

        $this->assertEquals((object) ['data' => 'foo'], $response->toObject());
    }

    /** @test */
    public function it_casts_to_a_string(): void
    {
        $original = new GuzzleResponse(200, [], '{"data":"foo"}');
        $response = new Response($original);

        $this->assertEquals('{"data":"foo"}', (string) $response);
        $this->assertEquals('{"data":"foo"}', $response->toString());
    }
}
