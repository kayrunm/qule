<?php

namespace Kayrunm\Qule;

use GuzzleHttp\ClientInterface;
use Kayrunm\Qule\Exceptions\UnregisteredConnection;

class QuleManager
{
    /**
     * The default path for new Qule connections to load
     *
     * @var string
     */
    protected $defaultPath;

    /**
     * The name of the default connection.
     *
     * @var string
     */
    protected $defaultConnection;

    /**
     * A list of registered connections.
     *
     * @var Qule[]
     */
    protected $connections = [];

    public function __construct(
        string $defaultPath = '',
        string $defaultConnection = 'default'
    ) {
        $this->defaultPath = $defaultPath;
        $this->defaultConnection = $defaultConnection;
    }

    /**
     * Return the Qule instance registered with the given key.
     */
    public function on(string $connection): Qule
    {
        if (! array_key_exists($connection, $this->connections)) {
            throw UnregisteredConnection::connection($connection);
        }

        return $this->connections[$connection];
    }

    /**
     * Register a new Qule connection.
     */
    public function register(
        string $key,
        ClientInterface $guzzle,
        ?string $path = null
    ): void {
        $this->connections[$key] = new Qule(
            $guzzle,
            $path ?? $this->defaultPath
        );
    }

    public function getConnections(): array
    {
        return $this->connections;
    }

    /**
     * Forward any calls directly on the manager to the default connection.
     */
    public function __call(string $method, array $arguments)
    {
        return $this->on($this->defaultConnection)->$method(...$arguments);
    }
}
