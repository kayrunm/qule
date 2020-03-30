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

    /**
     * The JSON-decoded response.
     *
     * @var object
     */
    protected $content;

    public function __construct(ResponseInterface $original)
    {
        $this->original = $original;
        $this->content = $this->toObject();
    }

    /**
     * Return the original response from the API.
     */
    public function getOriginalResponse(): ResponseInterface
    {
        return $this->original;
    }

    /**
     * Determine whether the given key is set in the JSON response
     * from the API.
     */
    public function __isset(string $key): bool
    {
        return isset($this->content->{$key});
    }

    /**
     * Get the data with the given key from the JSON response.
     *
     * @return mixed
     */
    public function __get(string $key)
    {
        return $this->content->{$key} ?? null;
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
