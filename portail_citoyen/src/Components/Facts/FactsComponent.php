<?php

declare(strict_types=1);

namespace App\Components\Facts;

use App\Etalab\AddressEtalabHandler;
use App\Form\Facts\FactsType;
use App\Form\Model\Address\AbstractSerializableAddress;
use App\Form\Model\Address\AddressEtalabModel;
use App\Form\Model\Address\AddressModel;
use App\Form\Model\EtalabInput;
use App\Form\Model\Facts\FactsModel;
use App\Session\ComplaintModel;
use App\Session\SessionHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveArg;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\LiveComponent\LiveCollectionTrait;

#[AsLiveComponent('facts')]
class FactsComponent extends AbstractController
{
    use ComponentWithFormTrait;
    use LiveCollectionTrait;
    use DefaultActionTrait;

    #[LiveProp]
    public bool $fromSummary;

    private FactsModel $factsModel;

    #[LiveProp(writable: true, exposed: ['addressSearch', 'addressId', 'addressSearchSaved'])]
    public EtalabInput $startAddressEtalabInput;

    #[LiveProp(writable: true, exposed: ['addressSearch', 'addressId', 'addressSearchSaved'])]
    public EtalabInput $endAddressEtalabInput;

    public function __construct(
        private readonly SessionHandler $sessionHandler,
        private readonly AddressEtalabHandler $addressEtalabHandler,
    ) {
        $this->factsModel = $this->sessionHandler->getComplaint()?->getFacts() ?? new FactsModel();
        $this->startAddressEtalabInput = new EtalabInput();
        $this->endAddressEtalabInput = new EtalabInput();

        $this->startAddressEtalabInput->setAddressSearch($this->factsModel->getAddress()?->getStartAddress()?->getLabel() ?? '');
        $this->endAddressEtalabInput->setAddressSearch($this->factsModel->getAddress()?->getEndAddress()?->getLabel() ?? '');
    }

    public function __invoke(): void
    {
        if (isset($this->formValues['placeNature']) && '1' === $this->formValues['placeNature']) { // Nature place is home
            $contactInformation = $this->sessionHandler->getComplaint()?->getIdentity()?->getContactInformation();

            if (!$this->factsModel->getAddress()?->getStartAddress() instanceof AbstractSerializableAddress) {
                if ($contactInformation?->getFrenchAddress() instanceof AbstractSerializableAddress) {
                    $address = $contactInformation->getFrenchAddress();
                    $this->factsModel->getAddress()?->setStartAddress($address);
                    if ($address instanceof AddressEtalabModel) {
                        $this->startAddressEtalabInput->setAddressSearch($address->getLabel() ?? '');
                        $this->startAddressEtalabInput->setAddressId($address->getId() ?? '');
                    }
                } else {
                    $this->factsModel->getAddress()?->setStartAddress(
                        (new AddressModel())->setLabel($contactInformation?->getForeignAddress())
                    );
                }
            }
        }
    }

    protected function instantiateForm(): FormInterface
    {
        return $this->createForm(
            FactsType::class,
            $this->factsModel,
        );
    }

    #[LiveAction]
    public function submit(#[LiveArg] bool $redirectToSummary = false): RedirectResponse
    {
        if (isset($this->formValues['address']['startAddress'])) {
            $this->formValues['address']['startAddress']['address'] = $this->startAddressEtalabInput->getAddressSearch();
            $this->formValues['address']['startAddress']['selectionId'] = $this->startAddressEtalabInput->getAddressId();
            $this->formValues['address']['startAddress']['query'] = $this->startAddressEtalabInput->getAddressSearchSaved();
        }

        if (isset($this->formValues['address']['endAddress'])) {
            $this->formValues['address']['endAddress']['address'] = $this->endAddressEtalabInput->getAddressSearch();
            $this->formValues['address']['endAddress']['selectionId'] = $this->endAddressEtalabInput->getAddressId();
            $this->formValues['address']['endAddress']['query'] = $this->endAddressEtalabInput->getAddressSearchSaved();
        }

        $this->submitForm();

        /** @var ComplaintModel $complaint */
        $complaint = $this->sessionHandler->getComplaint();
        /** @var FactsModel $facts */
        $facts = $this->getFormInstance()->getData();

        if (isset($this->formValues['address']['startAddress'])) {
            $startAddress = $this->addressEtalabHandler->getAddressModel($this->startAddressEtalabInput);
            $facts->getAddress()?->setStartAddress($startAddress);
        }

        if (isset($this->formValues['address']['endAddress'])) {
            $endAddress = $this->addressEtalabHandler->getAddressModel($this->endAddressEtalabInput);
            $facts->getAddress()?->setEndAddress($endAddress);
        }

        $this->sessionHandler->setComplaint($complaint->setFacts($facts));

        if (true === $redirectToSummary) {
            return $this->redirectToRoute('complaint_summary');
        }

        return $this->redirectToRoute('complaint_objects');
    }
}
