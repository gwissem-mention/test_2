<?php

declare(strict_types=1);

namespace App\Components\Identity;

use App\Etalab\AddressEtalabHandler;
use App\Etalab\AddressZoneChecker;
use App\Form\Identity\IdentityType;
use App\Form\Model\Address\AbstractSerializableAddress;
use App\Form\Model\Address\AddressEtalabModel;
use App\Form\Model\EtalabInput;
use App\Form\Model\Identity\IdentityModel;
use App\Session\ComplaintHandler;
use App\Session\ComplaintModel;
use App\Session\SessionHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
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

    /* Person Legal Representative must be hidden for the experimentation */
    // #[LiveProp(writable: true, exposed: ['addressSearch', 'addressId', 'addressSearchSaved'])]
    // public EtalabInput $representedPersonEtalabInput;

    public function __construct(
        private readonly SessionHandler $sessionHandler,
        private readonly AddressEtalabHandler $addressEtalabHandler,
        private readonly AddressZoneChecker $addressZoneChecker
    ) {
        $this->identityModel = $this->sessionHandler->getComplaint()?->getIdentity() ?? new IdentityModel();

        $this->contactInformationEtalabInput = $this->createEtalabInput($this->identityModel->getContactInformation()->getFrenchAddress());
        $this->corporationEtalabInput = $this->createEtalabInput($this->identityModel->getCorporation()?->getFrenchAddress());
        /* Person Legal Representative must be hidden for the experimentation */
        // $this->representedPersonEtalabInput = $this->createEtalabInput($this->identityModel->getRepresentedPersonContactInformation()?->getFrenchAddress());
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
    public function sameAddress(): void
    {
        if (isset($this->formValues['contactInformation']['foreignAddress'])) {
            /* Person Legal Representative must be hidden for the experimentation */
            // if (isset($this->formValues['representedPersonContactInformation'])) {
            //     if ($this->formValues['representedPersonContactInformation']['sameAddress']) {
            //         $this->formValues['representedPersonContactInformation']['foreignAddress'] = $this->formValues['contactInformation']['foreignAddress'];
            //     } else {
            //         $this->formValues['representedPersonContactInformation']['foreignAddress'] = null;
            //     }
            // }

            if (isset($this->formValues['corporation'])) {
                if ($this->formValues['corporation']['sameAddress']) {
                    $this->formValues['corporation']['foreignAddress'] = $this->formValues['contactInformation']['foreignAddress'];
                } else {
                    $this->formValues['corporation']['foreignAddress'] = null;
                }
            }
        }
    }

    #[LiveAction]
    public function submit(
        ComplaintHandler $complaintHandler,
        #[LiveArg] bool $redirectToSummary = false
    ): RedirectResponse {
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

        /* Person Legal Representative must be hidden for the experimentation */
        // if (isset($this->formValues['representedPersonContactInformation']['frenchAddress'])) {
        //    $this->formValues['representedPersonContactInformation']['frenchAddress']['address'] = $this->representedPersonEtalabInput->getAddressSearch();
        //     $this->formValues['representedPersonContactInformation']['frenchAddress']['selectionId'] = $this->representedPersonEtalabInput->getAddressId();
        //     $this->formValues['representedPersonContactInformation']['frenchAddress']['query'] = $this->representedPersonEtalabInput->getAddressSearchSaved();
        //
        //     if ($this->formValues['representedPersonContactInformation']['sameAddress']) {
        //         $this->formValues['representedPersonContactInformation']['frenchAddress']['address'] = $this->contactInformationEtalabInput->getAddressSearch();
        //         $this->formValues['representedPersonContactInformation']['frenchAddress']['selectionId'] = $this->contactInformationEtalabInput->getAddressId();
        //         $this->formValues['representedPersonContactInformation']['frenchAddress']['query'] = $this->contactInformationEtalabInput->getAddressSearchSaved();
        //     }
        // }

        $this->submitForm();

        /** @var ComplaintModel $complaint */
        $complaint = $this->sessionHandler->getComplaint();
        /** @var IdentityModel $identity */
        $identity = $this->getFormInstance()->getData();
        $address = null;

        if (isset($this->formValues['contactInformation']['frenchAddress'])) {
            $address = $this->addressEtalabHandler->getAddressModel($this->contactInformationEtalabInput);

            if (!$address instanceof AddressEtalabModel || false === $this->addressZoneChecker->isInsideGironde($address->getDepartment())) {
                throw new UnprocessableEntityHttpException('Form validation failed in component');
            }

            $contactInformation = $identity->getContactInformation();
            $contactInformation->setFrenchAddress($address);
            $identity->setContactInformation($contactInformation);
        } else {
            $identity->getContactInformation()->setFrenchAddress(null);
        }

        if (isset($this->formValues['corporation']['frenchAddress'])) {
            if (!$this->formValues['corporation']['sameAddress'] || !$address instanceof AbstractSerializableAddress) {
                $address = $this->addressEtalabHandler->getAddressModel($this->corporationEtalabInput);

                if (!$address instanceof AddressEtalabModel || false === $this->addressZoneChecker->isInsideGironde($address->getDepartment())) {
                    throw new UnprocessableEntityHttpException('Form validation failed in component');
                }
            }
            $corporation = $identity->getCorporation();
            $corporation?->setFrenchAddress($address);
            $identity->setCorporation($corporation);
        } else {
            $identity->getCorporation()?->setFrenchAddress(null);
        }

        /* Person Legal Representative must be hidden for the experimentation */
        // if (isset($this->formValues['representedPersonContactInformation']['frenchAddress'])) {
        //    if (!$this->formValues['representedPersonContactInformation']['sameAddress'] || !$address instanceof AbstractSerializableAddress) {
        //       $address = $this->addressEtalabHandler->getAddressModel($this->representedPersonEtalabInput);
        // }
        //    $identity->getRepresentedPersonContactInformation()?->setFrenchAddress($address);
        // } else {
        //     $identity->getRepresentedPersonContactInformation()?->setFrenchAddress(null);
        // }

        $complaint->setIdentity($identity);
        $affectedService = $complaintHandler->getAffectedService($complaint);

        $this->sessionHandler->setComplaint(
            $complaint
                ->setAffectedService($affectedService?->getServiceCodeForInstitution())
                ->setAffectedServiceInstitution($affectedService?->getInstitutionCode())
                ->setAppointmentRequired($complaintHandler->isAppointmentRequired($complaint))
        );

        if (true === $redirectToSummary) {
            return $this->redirectToRoute('complaint_summary');
        }

        return $this->redirectToRoute('complaint_facts');
    }

    private function createEtalabInput(AbstractSerializableAddress $address = null): EtalabInput
    {
        $etalabInput = new EtalabInput();

        if (null === $address) {
            return $etalabInput;
        }

        $etalabInput->setAddressSearch($address->getLabel() ?? '');
        if ($address instanceof AddressEtalabModel) {
            $etalabInput
                ->setAddressId($address->getId() ?? '')
                ->setAddressSearchSaved($address->getLabel() ?? '');
        }

        return $etalabInput;
    }
}
