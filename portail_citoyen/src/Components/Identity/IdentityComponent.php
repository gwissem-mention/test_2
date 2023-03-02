<?php

declare(strict_types=1);

namespace App\Components\Identity;

use App\Etalab\AddressEtalabHandler;
use App\Form\Identity\IdentityType;
use App\Form\Model\Address\AbstractSerializableAddress;
use App\Form\Model\EtalabInput;
use App\Form\Model\Identity\IdentityModel;
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

#[AsLiveComponent('identity')]
class IdentityComponent extends AbstractController
{
    use ComponentWithFormTrait;
    use DefaultActionTrait;

    #[LiveProp]
    public bool $fromSummary = false;

    private IdentityModel $identityModel;

    #[LiveProp(writable: true, exposed: ['addressSearch', 'addressId', 'addressSearchSaved'])]
    public EtalabInput $contactInformationEtalabInput;

    #[LiveProp(writable: true, exposed: ['addressSearch', 'addressId', 'addressSearchSaved'])]
    public EtalabInput $corporationEtalabInput;

    #[LiveProp(writable: true, exposed: ['addressSearch', 'addressId', 'addressSearchSaved'])]
    public EtalabInput $representedPersonEtalabInput;

    public function __construct(
        private readonly SessionHandler $sessionHandler,
        private readonly AddressEtalabHandler $addressEtalabHandler
    ) {
        $this->identityModel = $this->sessionHandler->getComplaint()?->getIdentity() ?? new IdentityModel();
        $this->contactInformationEtalabInput = new EtalabInput();
        $this->corporationEtalabInput = new EtalabInput();
        $this->representedPersonEtalabInput = new EtalabInput();

        $this->contactInformationEtalabInput->setAddressSearch($this->identityModel->getContactInformation()?->getFrenchAddress()?->getLabel() ?? '');
        $this->corporationEtalabInput->setAddressSearch($this->identityModel->getCorporation()?->getFrenchAddress()?->getLabel() ?? '');
        $this->representedPersonEtalabInput->setAddressSearch($this->identityModel->getRepresentedPersonContactInformation()?->getFrenchAddress()?->getLabel() ?? '');
    }

    protected function instantiateForm(): FormInterface
    {
        return $this->createForm(
            IdentityType::class,
            $this->identityModel,
            [
                'is_france_connected' => $this->sessionHandler->getComplaint()?->isFranceConnected(),
            ]
        );
    }

    #[LiveAction]
    public function submit(#[LiveArg] bool $redirectToSummary = false): RedirectResponse
    {
        if (isset($this->formValues['contactInformation']['frenchAddress'])) {
            $this->formValues['contactInformation']['frenchAddress']['address'] = $this->contactInformationEtalabInput->getAddressSearch();
            $this->formValues['contactInformation']['frenchAddress']['selectionId'] = $this->contactInformationEtalabInput->getAddressId();
            $this->formValues['contactInformation']['frenchAddress']['query'] = $this->contactInformationEtalabInput->getAddressSearchSaved();
        }

        if (isset($this->formValues['corporation']['frenchAddress'])) {
            $this->formValues['corporation']['frenchAddress']['address'] = $this->corporationEtalabInput->getAddressSearch();
            $this->formValues['corporation']['frenchAddress']['selectionId'] = $this->corporationEtalabInput->getAddressId();
            $this->formValues['corporation']['frenchAddress']['query'] = $this->corporationEtalabInput->getAddressSearchSaved();

            if ($this->formValues['corporation']['sameAddress']) {
                $this->formValues['corporation']['frenchAddress']['address'] = $this->contactInformationEtalabInput->getAddressSearch();
                $this->formValues['corporation']['frenchAddress']['selectionId'] = $this->contactInformationEtalabInput->getAddressId();
                $this->formValues['corporation']['frenchAddress']['query'] = $this->contactInformationEtalabInput->getAddressSearchSaved();
            }
        }

        if (isset($this->formValues['representedPersonContactInformation']['frenchAddress'])) {
            $this->formValues['representedPersonContactInformation']['frenchAddress']['address'] = $this->representedPersonEtalabInput->getAddressSearch();
            $this->formValues['representedPersonContactInformation']['frenchAddress']['selectionId'] = $this->representedPersonEtalabInput->getAddressId();
            $this->formValues['representedPersonContactInformation']['frenchAddress']['query'] = $this->representedPersonEtalabInput->getAddressSearchSaved();

            if ($this->formValues['representedPersonContactInformation']['sameAddress']) {
                $this->formValues['representedPersonContactInformation']['frenchAddress']['address'] = $this->contactInformationEtalabInput->getAddressSearch();
                $this->formValues['representedPersonContactInformation']['frenchAddress']['selectionId'] = $this->contactInformationEtalabInput->getAddressId();
                $this->formValues['representedPersonContactInformation']['frenchAddress']['query'] = $this->contactInformationEtalabInput->getAddressSearchSaved();
            }
        }

        $this->submitForm();

        /** @var ComplaintModel $complaint */
        $complaint = $this->sessionHandler->getComplaint();
        /** @var IdentityModel $identity */
        $identity = $this->getFormInstance()->getData();
        $address = null;

        if (isset($this->formValues['contactInformation']['frenchAddress'])) {
            $address = $this->addressEtalabHandler->getAddressModel($this->contactInformationEtalabInput);
            $contactInformation = $identity->getContactInformation();
            $contactInformation?->setFrenchAddress($address);
            $identity->setContactInformation($contactInformation);
        }

        if (isset($this->formValues['corporation']['frenchAddress'])) {
            if (!$this->formValues['corporation']['sameAddress'] || !$address instanceof AbstractSerializableAddress) {
                $address = $this->addressEtalabHandler->getAddressModel($this->corporationEtalabInput);
            }
            $corporation = $identity->getCorporation();
            $corporation?->setFrenchAddress($address);
            $identity->setCorporation($corporation);
        }

        if (isset($this->formValues['representedPersonContactInformation']['frenchAddress'])) {
            if (!$this->formValues['representedPersonContactInformation']['sameAddress'] || !$address instanceof AbstractSerializableAddress) {
                $address = $this->addressEtalabHandler->getAddressModel($this->representedPersonEtalabInput);
            }
            $identity->getRepresentedPersonContactInformation()?->setFrenchAddress($address);
        }

        $complaint->setIdentity($identity);
        $this->sessionHandler->setComplaint($complaint);

        if (true === $redirectToSummary) {
            return $this->redirectToRoute('complaint_summary');
        }

        return $this->redirectToRoute('complaint_facts');
    }
}
