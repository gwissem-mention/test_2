<?php

declare(strict_types=1);

namespace App\Components\Objects;

use App\Etalab\AddressEtalabHandler;
use App\Form\Model\Address\AddressEtalabModel;
use App\Form\Model\EtalabInput;
use App\Form\Model\FileModel;
use App\Form\Model\Objects\ObjectsModel;
use App\Form\Objects\ObjectsType;
use App\Session\ComplaintHandler;
use App\Session\ComplaintModel;
use App\Session\SessionHandler;
use League\Flysystem\FilesystemException;
use League\Flysystem\FilesystemOperator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveArg;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\LiveComponent\LiveCollectionTrait;

#[AsLiveComponent('objects')]
class ObjectsComponent extends AbstractController
{
    use ComponentWithFormTrait;
    use LiveCollectionTrait;
    use DefaultActionTrait;

    #[LiveProp]
    public bool $fromSummary;

    /**
     * @var array<int, array<string, string>>
     */
    #[LiveProp(writable: true)]
    public array $files = [];

    /**
     * @var array<int, array<string, string>>
     */
    #[LiveProp(writable: true)]
    public array $documentOwnerAddresses = [];

    private ObjectsModel $objectsModel;

    public function __construct(
        private readonly SessionHandler $sessionHandler,
        private readonly AddressEtalabHandler $addressEtalabHandler,
    ) {
        $this->objectsModel = $this->sessionHandler->getComplaint()?->getObjects() ?? new ObjectsModel();

        if ($this->objectsModel->getObjects()->isEmpty()) {
            $this->documentOwnerAddresses[] = [
                'addressSearch' => '',
                'addressId' => '',
                'addressSearchSaved' => '',
            ];
        } else {
            foreach ($this->objectsModel->getObjects() as $object) {
                $documentOwnerAddress = $object->getDocumentAdditionalInformation()?->getDocumentOwnerAddress();
                $this->documentOwnerAddresses[] = [
                    'addressSearch' => $documentOwnerAddress?->getLabel() ?? '',
                    'addressId' => ($documentOwnerAddress instanceof AddressEtalabModel) ? $documentOwnerAddress->getId() ?? '' : '',
                    'addressSearchSaved' => ($documentOwnerAddress instanceof AddressEtalabModel) ? $documentOwnerAddress->getLabel() ?? '' : '',
                ];
            }
        }
    }

    protected function instantiateForm(): FormInterface
    {
        return $this->createForm(ObjectsType::class, $this->sessionHandler->getComplaint()?->getObjects() ?? new ObjectsModel());
    }

    #[LiveAction]
    public function addObject(): void
    {
        $this->documentOwnerAddresses[] = [
            'addressSearch' => '',
            'addressId' => '',
            'addressSearchSaved' => '',
        ];
    }

    #[LiveAction]
    public function removeObject(#[LiveArg] int $index): void
    {
        array_splice($this->documentOwnerAddresses, $index, 1);
    }

    /**
     * @throws FilesystemException
     */
    #[LiveAction]
    public function submit(
        FilesystemOperator $defaultStorage,
        ComplaintHandler $complaintHandler,
        #[LiveArg] bool $redirectToSummary = false
    ): RedirectResponse {
        foreach ($this->documentOwnerAddresses as $addressIndex => $address) {
            if (isset($this->formValues['objects'][$addressIndex]['documentType']['documentAdditionalInformation']['documentOwnerAddress'])) {
                $this->formValues['objects'][$addressIndex]['documentType']['documentAdditionalInformation']['documentOwnerAddress']['address'] = $address['addressSearch'];
                $this->formValues['objects'][$addressIndex]['documentType']['documentAdditionalInformation']['documentOwnerAddress']['selectionId'] = $address['addressId'];
                $this->formValues['objects'][$addressIndex]['documentType']['documentAdditionalInformation']['documentOwnerAddress']['query'] = $address['addressSearchSaved'];
            }
        }

        $this->submitForm();

        /** @var ComplaintModel $complaint */
        $complaint = $this->sessionHandler->getComplaint();

        /** @var ObjectsModel $objects */
        $objects = $this->getFormInstance()->getData();

        foreach ($this->documentOwnerAddresses as $addressIndex => $address) {
            if (isset($this->formValues['objects'][$addressIndex]['documentType']['documentAdditionalInformation']['documentOwnerAddress'])) {
                $documentOwnerAddress = $this->addressEtalabHandler->getAddressModel(
                    new EtalabInput(
                        $address['addressSearch'],
                        $address['addressId'],
                        $address['addressSearchSaved']
                    )
                );
                $objects->getObjects()[$addressIndex]?->getDocumentAdditionalInformation()?->setDocumentOwnerAddress($documentOwnerAddress);
            }
        }

        foreach ($this->files as $objectIndex => $files) {
            foreach ($files as $originalName => $name) {
                $defaultStorage->writeStream($name, fopen(sys_get_temp_dir().'/'.$name, 'rb'));
                $file = $defaultStorage->readStream($name);
                $uploadedFile = new UploadedFile(stream_get_meta_data($file)['uri'], $originalName);
                $objects->getObjects()->get($objectIndex)?->addFile(new FileModel($uploadedFile->getClientOriginalName(), $uploadedFile->getFilename(), (string) $uploadedFile->getMimeType(), (int) $uploadedFile->getSize()));
            }
        }

        $this->sessionHandler->setComplaint(
            $complaint
                ->setObjects($objects)
                ->setAppointmentRequired($complaintHandler->isAppointmentRequired($complaint))
        );

        if (true === $redirectToSummary) {
            return $this->redirectToRoute('complaint_summary');
        }

        return $this->redirectToRoute('complaint_additional_information');
    }
}
