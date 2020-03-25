<?php

namespace Kayrunm\Qule;

use GuzzleHttp\ClientInterface;
use Kayrunm\Qule\Contracts\InlineQuery;
use Kayrunm\Qule\Exceptions\QueryFileDoesntExist;
use Kayrunm\Qule\Query;
use Psr\Http\Message\ResponseInterface;

class Qule
{
    /** @var \GuzzleHttp\ClientInterface */
    protected $guzzle;

    /** @var string */
    protected $path;

    public function __construct(ClientInterface $guzzle, string $path = '')
    {
        $this->guzzle = $guzzle;
        $this->path = $path;
    }

    /**
     * Use the given query class to make a request to the GraphQL API.
     *
     * @param  \Kayrunm\Qule\Query  $query  The query class to use.
     * @param  array  $variables  The variables for the query.
     */
    public function query(Query $query, array $variables = []): ResponseInterface
    {
        return $this->guzzle->request(
            $query->getMethod(),
            $query->getEndpoint(),
            [
                'headers' => [
                    'Content-Type' => $query->getContentType(),
                ],
                'body' => $this->buildQuery($query, $variables)
            ]
        );
    }

    /** @return string */
    protected function buildQuery(Query $query, array $variables): string
    {
        $queryString = $this->getQueryString($query);

        if ($query->withoutWrapping) {
            return $queryString;
        }

        $body = array_merge([
            'query' => $queryString,
            'variables' => array_merge($query->getVariables(), $variables),
        ], $query->getExtra());

        return json_encode(array_filter($body));
    }

    protected function getQueryString(Query $query): string
    {
        if ($query instanceof InlineQuery) {
            return $query->getQuery();
        }

        return $this->loadFile($query);
    }

    protected function loadFile(Query $query): string
    {
        $filepath = $this->path . '/' . $query->getFile() . '.graphql';

        if (! file_exists($filepath)) {
            throw QueryFileDoesntExist::file($filepath);
        }

        return file_get_contents($filepath);
    }
}
