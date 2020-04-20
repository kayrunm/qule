<?php

namespace Kayrunm\Qule;

use Psr\Http\Message\ResponseInterface;

class Response
{
    /**
     * The original response returned from the API.
     *
     * @var \Psr\Http\Message\ResponseInterface
     */
    protected $original;

    public function __construct(ResponseInterface $original)
    {
        $this->original = $original;

        $this->setUp($this->toObject());
    }

    /**
     * This method can be used to set up your response. It receives
     * the JSON-decoded response as an argument.
     */
    protected function setUp(?object $data): void
    {
    }

    /**
     * Return the original response from the API.
     */
    public function getOriginalResponse(): ResponseInterface
    {
        return $this->original;
    }

    public function toArray(): ?array
    {
        return json_decode($this->toString(), true);
    }

    public function toObject(): ?object
    {
        return json_decode($this->toString());
    }

    public function toString(): string
    {
        return (string) $this->original->getBody();
    }

    public function __toString(): string
    {
        return $this->toString();
    }
}
