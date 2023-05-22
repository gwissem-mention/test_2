<?php

declare(strict_types=1);

namespace App\Components;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent('google_maps')]
class GoogleMapsComponent
{
    use DefaultActionTrait;

    #[LiveProp]
    public string $apiKey;

    #[LiveProp]
    public string $id;

    #[LiveProp]
    public ?string $searchBoxActionUrl = null;

    public function __construct(private readonly ParameterBagInterface $parameterBag)
    {
    }

    public function mount(): void
    {
        /** @var string|null $googleMapsApiKey */
        $googleMapsApiKey = $this->parameterBag->get('app.google_maps_api_key');
        $this->apiKey = strval($googleMapsApiKey);
    }
}
