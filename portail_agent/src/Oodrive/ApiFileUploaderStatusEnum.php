<?php

declare(strict_types=1);

namespace App\Oodrive;

enum ApiFileUploaderStatusEnum
{
    case UPLOADED;
    case REPLACED;
    case IGNORED;
}
