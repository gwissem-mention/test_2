<?php

declare(strict_types=1);

namespace App\Components\Identity;

use App\Etalab\AddressEtalabHandler;
use App\Form\Identity\IdentityType;
use App\Form\Model\Identity\EmbedAddressInterface;
use App\Form\Model\Identity\IdentityModel;
use App\Session\ComplaintModel;
use App\Session\SessionHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent('identity')]
class IdentityComponent extends AbstractController
{
    use ComponentWithFormTrait;
    use DefaultActionTrait;

    public function __construct(
        private readonly SessionHandler $sessionHandler,
        private readonly AddressEtalabHandler $addressEtalabHandler
    ) {
    }

    protected function instantiateForm(): FormInterface
    {
        return $this->createForm(
            IdentityType::class,
            $this->sessionHandler->getComplaint()?->getIdentity() ?? new IdentityModel(),
            [
                'is_france_connected' => $this->sessionHandler->getComplaint()?->isFranceConnected(),
            ]
        );
    }

    #[LiveAction]
    public function submit(): RedirectResponse
    {
        $this->submitForm();

        /** @var ComplaintModel $complaint */
        $complaint = $this->sessionHandler->getComplaint();
        /** @var IdentityModel $identity */
        $identity = $this->getFormInstance()->getData();
        $etalabFields = [
            'contactInformation' => $identity->getContactInformation(),
            'representedPersonContactInformation' => $identity->getRepresentedPersonContactInformation(),
            'corporation' => $identity->getCorporation(),
        ];

        /**
         * @var EmbedAddressInterface $model
         */
        foreach ($etalabFields as $form => $model) {
            if (isset($this->formValues[$form]['frenchAddress'])) {
                $frenchAddress = $this->formValues[$form]['frenchAddress'];
                $model->setFrenchAddress(
                    $this->addressEtalabHandler->getAddressModel(
                        $frenchAddress['address'],
                        $frenchAddress['query'],
                        $frenchAddress['selectionId']
                    )
                );
            }
        }

        $this->sessionHandler->setComplaint($complaint->setIdentity($identity));

        return $this->redirectToRoute('complaint_facts');
    }
}
