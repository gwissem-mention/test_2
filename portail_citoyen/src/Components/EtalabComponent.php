<?php

declare(strict_types=1);

namespace App\Components;

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

    /**
     * @var string[] $errors
     */
    #[LiveProp]
    public array $errors = [];

    #[LiveProp(writable: true)]
    public bool $disabled = false;

    public function __construct(private readonly EtalabApiClientInterface $etalabAddressApiClient)
    {
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
                $this->addressSearchSaved = $this->addressSearch;
                $this->addressSearch = $address['properties']['label'];
                $this->autocompleteResults = [];
                $this->addressId = $addressId;
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
