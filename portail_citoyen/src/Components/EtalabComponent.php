<?php

declare(strict_types=1);

namespace App\Components;

use App\Etalab\AddressZoneChecker;
use App\Etalab\EtalabApiClientInterface;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveArg;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent('etalab_component')]
class EtalabComponent
{
    use DefaultActionTrait;

    private const MIN_CHAR_TO_LAUNCH_SEARCH = 5;

    #[LiveProp(writable: true)]
    private string $addressSearch = '';

    /**
     * @var array<int, array<string, array<string, int|string|float>>> $autocompleteResults
     */
    #[LiveProp(writable: true)]
    private array $autocompleteResults = [];

    #[LiveProp(writable: true)]
    public string $addressId = '';

    #[LiveProp(writable: true)]
    public string $addressSearchSaved = '';

    #[LiveProp]
    public string $inputName;

    #[LiveProp]
    public bool $dataGirondeEnabled;

    #[LiveProp]
    public bool $isBetweenTwoPlaces = false;

    #[LiveProp(writable: true)]
    public ?string $latitude = null;

    #[LiveProp(writable: true)]
    public ?string $longitude = null;

    #[LiveProp(writable: true)]
    public ?string $postcode = null;

    /**
     * @var string[] $errors
     */
    #[LiveProp]
    public array $errors = [];

    #[LiveProp(writable: true)]
    public bool $disabled = false;

    #[LiveProp(writable: true)]
    public bool $required = true;

    public function __construct(
        private readonly EtalabApiClientInterface $etalabAddressApiClient,
        private readonly AddressZoneChecker $addressZoneChecker
    ) {
    }

    public function __invoke(): void
    {
        $this->addressId = '';
        if (strlen($this->addressSearch) > self::MIN_CHAR_TO_LAUNCH_SEARCH) {
            /**
             * @var array<string, array<int, array<string, array<string, int|string|float>>>> $addresses
             */
            $addresses = $this->etalabAddressApiClient->search($this->addressSearch, 5);

            $this->autocompleteResults = $addresses['features'] ?? [];
        }
    }

    #[LiveAction]
    public function selectAddress(#[LiveArg] string $addressId): void
    {
        /** @var array<string, array<string, string>> $address */
        foreach ($this->autocompleteResults as $address) {
            if ($address['properties']['id'] === $addressId) {
                if (true === $this->dataGirondeEnabled && false === $this->isBetweenTwoPlaces) {
                    $departmentNumber = substr($address['properties']['citycode'], 0, 2);

                    if (!$this->addressZoneChecker->isInsideGironde($departmentNumber)) {
                        $this->errors[] = 'Uniquement les adresses des faits commis en Gironde sont acceptées';

                        return;
                    }
                }

                $this->addressSearchSaved = $this->addressSearch;
                $this->addressSearch = $address['properties']['label'];
                $this->autocompleteResults = [];
                $this->addressId = $addressId;
                $this->latitude = (string) $address['geometry']['coordinates'][1];
                $this->longitude = (string) $address['geometry']['coordinates'][0];
                $this->postcode = $address['properties']['postcode'];
            }
        }
    }

    public function getAddressSearch(): string
    {
        return $this->addressSearch;
    }

    public function setAddressSearch(string $addressSearch): void
    {
        $this->addressSearch = $addressSearch;
    }

    /**
     * @param array<int, array<string, array<string, int|string|float>>> $autocompleteResults
     */
    public function setAutocompleteResults(array $autocompleteResults): void
    {
        $this->autocompleteResults = $autocompleteResults;
    }

    /**
     * @return array<int, array<string, array<string, int|string|float>>>
     */
    public function getAutocompleteResults(): array
    {
        return $this->autocompleteResults;
    }
}
