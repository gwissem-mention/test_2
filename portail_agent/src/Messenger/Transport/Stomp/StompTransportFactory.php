<?php

namespace App\Messenger\Transport\Stomp;

use Symfony\Component\Messenger\Transport\Serialization\SerializerInterface;
use Symfony\Component\Messenger\Transport\TransportFactoryInterface;
use Symfony\Component\Messenger\Transport\TransportInterface;

class StompTransportFactory implements TransportFactoryInterface
{
    /**
     * @param array{queue: string, transport_name: string} $options
     */
    public function createTransport(string $dsn, array $options, SerializerInterface $serializer): TransportInterface
    {
        return new StompTransport(Connection::fromDsn($dsn, $options), $serializer);
    }

    /**
     * @param array{queue: string, transport_name: string} $options
     */
    public function supports(string $dsn, array $options): bool
    {
        return str_starts_with($dsn, 'stomp://');
    }
}
