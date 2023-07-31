<?php

declare(strict_types=1);

namespace App\Messenger\Transport\Stomp;

use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Transport\Serialization\SerializerInterface;
use Symfony\Component\Messenger\Transport\TransportInterface;

class StompTransport implements TransportInterface
{
    private StompSender $sender;

    public function __construct(
        private readonly Connection $connection,
        private readonly SerializerInterface $serializer,
    ) {
    }

    public function send(Envelope $envelope): Envelope
    {
        return $this->getSender()->send($envelope);
    }

    private function getSender(): StompSender
    {
        return $this->sender ??= new StompSender($this->connection, $this->serializer);
    }

    public function get(): iterable
    {
        throw new \RuntimeException('Not implemented');
    }

    public function ack(Envelope $envelope): void
    {
        throw new \RuntimeException('Not implemented');
    }

    public function reject(Envelope $envelope): void
    {
        throw new \RuntimeException('Not implemented');
    }
}
