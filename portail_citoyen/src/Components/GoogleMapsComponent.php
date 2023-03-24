<?php

declare(strict_types=1);

namespace App\Components;

use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent('google_maps')]
class GoogleMapsComponent
{
    use DefaultActionTrait;
}
