<?php

declare(strict_types=1);

namespace App\Messenger\Transport\Stomp;

use http\Exception\InvalidArgumentException;
use Stomp\Client;
use Stomp\StatefulStomp;
use Stomp\Transport\Message;

class Connection
{
    private StatefulStomp $stomp;

    /**
     * @var array{queue: string, transport_name: string}
     */
    private array $options;

    /**
     * @param array{queue: string, transport_name: string} $options
     */
    private function __construct(Client $client, array $options)
    {
        $this->stomp = new StatefulStomp($client);
        $this->options = $options;
    }

    /**
     * @param array{queue: string, transport_name: string} $options
     */
    public static function fromDsn(#[\SensitiveParameter] string $dsn, array $options): self
    {
        if (false === str_starts_with($dsn, 'stomp://')) {
            throw new InvalidArgumentException(sprintf('DSN "%s" is not supported by the Stomp transport.', $dsn));
        }

        $dsn = 'tcp'.substr($dsn, 5);

        $credentials = static::extractCredentialsFromDSN($dsn);

        $client = new Client($dsn);
        $client->setLogin($credentials['login'], $credentials['password']);

        return new self($client, $options);
    }

    public function send(string $message): void
    {
        $queueName = $this->options['queue'];

        if (!is_string($queueName)) {
            throw new \RuntimeException(sprintf('The queue for transport %s is not configured.', $this->options['transport_name']));
        }

        $this->stomp->send($queueName, new Message($message));
    }

    /**
     * @return string[]
     */
    public static function extractCredentialsFromDSN(string $dsn): array
    {
        $credentials = ['login' => '', 'password' => ''];

        if (preg_match('/stomp:\/\/([^:]+):([^@]+)@.+/', $dsn, $matches)) {
            $credentials['login'] = $matches[1];
            $credentials['password'] = $matches[2];
        }

        return $credentials;
    }
}
