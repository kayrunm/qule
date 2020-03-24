<?php

namespace Kayrunm\Qule;

abstract class Query
{
    /**
     * This is the HTTP method that Qule should use when querying your
     * GraphQL API endpoint.
     *
     * @var string
     */
    protected $method = 'GET';

    /**
     * This is the endpoint that Qule should send the query to when
     * querying your GraphQL API.
     *
     * @var string
     */
    protected $endpoint = '';

    /**
     * This is the content type header that your GraphQL API expects
     * to receive. Usually this is application/graphql, but some
     * APIs will expect application/json.
     *
     * @var string
     */
    protected $contentType = 'application/graphql';

    /**
     * The variables to send along with the query. You can set default
     * variables for your query directly in this property in your
     * extensions of this class.
     *
     * @var array
     */
    protected $variables = [];

    /**
     * The file to load the GraphQL query from. If you leave this blank, you
     * must add the `getQuery` method to return your query as a string.
     *
     * @var string|null
     */
    protected $file;

    /**
     * Any extra data to append to the query, e.g. the operation name.
     *
     * @var array
     */
    protected $extra = [];

    /**
     * If this is set to true, the query will not be wrapped in a JSON object
     * when querying the API.
     *
     * @var bool
     */
    public $withoutWrapping = false;

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getEndpoint(): string
    {
        return $this->endpoint;
    }

    public function getContentType(): string
    {
        return $this->contentType;
    }

    public function getVariables(): array
    {
        return $this->variables;
    }

    public function getFile(): ?string
    {
        return $this->file;
    }

    public function getExtra(): array
    {
        return $this->extra;
    }
}
