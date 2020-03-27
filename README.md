
# Qule

**Qule** lets you wrap up your GraphQL queries into clean, no-nonsense classes.

## Installation

```
composer require kayrunm/qule
```

## Creating your first query

_In these examples, we will be setting Qule up to work with a Shopify store._

We will start by creating your first query class. All query classes must extend from the base `Kayrunm\Qule\Query` class, which has a few defaults to get you going. The main properties you will ever need to concern yourself with overwriting are the `method`, `endpoint` and `file`.

```php
<?php

use Kayrunm\Qule\Query;

class GetCustomers extends Query
{
    protected $method = 'POST';
    protected $endpoint = 'graphql.json';
    protected $file = 'get-customers';
}
```

Now, you need to set up Qule itself. Qule needs two parameters to get up and running: a Guzzle client: which makes the requests under the hood; and a path to the directory which houses your GraphQL queries.

When setting up your Guzzle client, you will want to set the `base_uri` as well as any authentication you need. After that, just create a new instance of `Qule` with your client and your query path and you're almost on your way to making your first query:

```php
$client = new \GuzzleHttp\Client([
    'base_uri' => 'https://qule.myshopify.com/admin/2020-01/',
    'headers' => [
        'X-Shopify-Access-Token' => 'abcdef1234567890',
    ],
]);

$qule = new \Kayrunm\Qule\Qule($client, base_path('resources/queries/'));
```

Finally, onto the good stuff. To execute your query, simply call Qule's `query` method with an instance of the query you want to run, which will then return a `Response` class wrapping the data you get back from your API.

```php
$response = $qule->query(new GetCustomers);
```

The `Response` class is just a wrapper for a Guzzle response which automatically decodes the response from your API. You can access the decoded object directly as though they were properties, like so:

```php
$firstCustomer = $response->data->shop->customers->edges[0];
```

## Passing variables

When passing variables for your query, you simply pass an argument as the second argument to Qule's `query` method, like so:

```php
$qule->query(new GetCustomersBySurname, [
    'surname' => 'Marshall'
]);
```

## Laravel

In Laravel, you could do the following in a service provider so that you can use dependency injection to avoid setting Qule up each time you want to use it:

```php
use Kayrunm\Qule\Qule;

// ...

public function register() {
    $this->app->singleton(Qule::class, function () {
        $client = new \GuzzleHttp\Client([
            'base_uri' => 'https://qule.myshopify.com/admin/2020-01/',
            'headers' => ['X-Shopify-Access-Token' => 'abcdef1234567890'],
        ]);

        return new \Kayrunm\Qule\Qule($client);
    });
}
```
