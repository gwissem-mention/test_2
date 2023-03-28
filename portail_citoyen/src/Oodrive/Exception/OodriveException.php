<?php

namespace App\Oodrive\Exception;

use Symfony\Contracts\HttpClient\ResponseInterface;

class OodriveException extends ProxyHttpException
{
    public function __construct(ResponseInterface $response)
    {
        parent::__construct($response);

        try {
            /** @var array<string, string> $responseContent */
            $responseContent = json_decode($response->getContent(false), true, 512, JSON_THROW_ON_ERROR);

            if (isset($responseContent['code'])) {
                $this->message .= "\n".$responseContent['code'];
            }
            if (isset($responseContent['description'])) {
                $this->message .= ' : '.$responseContent['description'];
            }

            if (isset($responseContent['details'])) {
                $this->message .= "\n".json_encode($responseContent['details']);
            }
        } catch (\JsonException $e) {
        }
    }
}
