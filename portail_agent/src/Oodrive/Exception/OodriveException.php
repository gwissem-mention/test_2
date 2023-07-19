<?php

namespace App\Oodrive\Exception;

use Symfony\Contracts\HttpClient\ResponseInterface;

class OodriveException extends ProxyHttpException
{
    private OodriveErrorsEnum $errorCode;
    private string $description = '';
    private string $encodedDetails = '';

    public function __construct(ResponseInterface $response)
    {
        parent::__construct($response);

        try {
            /** @var array<string, string> $responseContent */
            $responseContent = json_decode($response->getContent(false), true, 512, JSON_THROW_ON_ERROR);

            if (isset($responseContent['code'])) {
                $this->errorCode = OodriveErrorsEnum::from($responseContent['code']);
                $this->message .= "\n".$responseContent['code'];
            }
            if (isset($responseContent['description'])) {
                $this->description = $responseContent['description'];
                $this->message .= ' : '.$this->description;
            }

            if (isset($responseContent['details'])) {
                $encodedDetails = json_encode($responseContent['details']);
                if (is_string($encodedDetails)) {
                    $this->encodedDetails = $encodedDetails;
                    $this->message .= "\n".$this->encodedDetails;
                }
            }
        } catch (\JsonException $e) {
        }
    }

    public function getErrorCode(): OodriveErrorsEnum
    {
        return $this->errorCode;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getEncodedDetails(): string
    {
        return $this->encodedDetails;
    }
}
