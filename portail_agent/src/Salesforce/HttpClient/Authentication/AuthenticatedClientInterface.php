<?php

declare(strict_types=1);

namespace App\Salesforce\HttpClient\Authentication;

use Symfony\Contracts\HttpClient\HttpClientInterface;

interface AuthenticatedClientInterface extends HttpClientInterface
{
}
