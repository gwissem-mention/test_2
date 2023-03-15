<?php

namespace App\Oodrive\Exception;

use Symfony\Component\HttpClient\Exception\HttpExceptionTrait;
use Symfony\Contracts\HttpClient\Exception\HttpExceptionInterface;

class ProxyHttpException extends \RuntimeException implements HttpExceptionInterface
{
    use HttpExceptionTrait;
}
