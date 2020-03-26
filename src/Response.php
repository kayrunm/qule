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
    protected $response;

    public function __construct(ResponseInterface $original)
    {
        $this->original = $original;
        $this->response = json_decode($original->getBody()->getContents());
    }

    /**
     * Return the original response from the API.
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function getOriginalResponse(): ResponseInterface
    {
        return $this->original;
    }

    public function toArray(): array
    {
        return (array) $this->response;
    }

    /**
     * Determine whether the given key is set in the JSON response
     * from the API.
     *
     * @param string $key
     * @return boolean
     */
    public function __isset(string $key): bool
    {
        return isset($this->response->{$key});
    }

    /**
     * Get the data with the given key from the JSON response.
     *
     * @return mixed
     */
    public function __get(string $key)
    {
        return $this->response->{$key} ?? null;
    }
}
