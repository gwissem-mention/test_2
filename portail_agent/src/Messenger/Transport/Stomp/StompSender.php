<?php

namespace App\Messenger\Transport\Stomp;

use App\Messenger\InformationCenter\InfocentreMessage;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Transport\Sender\SenderInterface;
use Symfony\Component\Messenger\Transport\Serialization\SerializerInterface;

class StompSender implements SenderInterface
{
    public function __construct(
        private readonly Connection $connection,
        private readonly SerializerInterface $serializer,
    ) {
    }

    public function send(Envelope $envelope): Envelope
    {
        /** @var InfocentreMessage $message */
        $message = $envelope->getMessage();
        $jsonMessage = json_encode($message->getData());

        if (!is_string($jsonMessage)) {
            throw new \RuntimeException(sprintf('Message with envelope %s can\'t be parsed', json_encode($this->serializer->encode($envelope))));
        }

        $this->connection->send($jsonMessage);

        return $envelope;
    }
}
