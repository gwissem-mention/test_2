<?php

declare(strict_types=1);

namespace App\Components\Objects;

use App\Form\Model\FileModel;
use App\Form\Model\Objects\ObjectsModel;
use App\Form\Objects\ObjectsType;
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

    public function __construct(private readonly SessionHandler $sessionHandler)
    {
    }

    protected function instantiateForm(): FormInterface
    {
        return $this->createForm(ObjectsType::class, $this->sessionHandler->getComplaint()?->getObjects() ?? new ObjectsModel());
    }

    /**
     * @throws FilesystemException
     */
    #[LiveAction]
    public function submit(FilesystemOperator $defaultStorage, #[LiveArg] bool $redirectToSummary = false): RedirectResponse
    {
        $this->submitForm();

        /** @var ComplaintModel $complaint */
        $complaint = $this->sessionHandler->getComplaint();

        /** @var ObjectsModel $objects */
        $objects = $this->getFormInstance()->getData();

        foreach ($this->files as $objectIndex => $files) {
            foreach ($files as $originalName => $name) {
                $defaultStorage->writeStream($name, fopen(sys_get_temp_dir().'/'.$name, 'rb'));
                $file = $defaultStorage->readStream($name);
                $uploadedFile = new UploadedFile(stream_get_meta_data($file)['uri'], $originalName);
                $objects->getObjects()->get($objectIndex)?->addFile(new FileModel($uploadedFile->getClientOriginalName(), $uploadedFile->getFilename(), (string) $uploadedFile->getMimeType(), (int) $uploadedFile->getSize()));
            }
        }

        $this->sessionHandler->setComplaint($complaint->setObjects($objects));

        if (true === $redirectToSummary) {
            return $this->redirectToRoute('complaint_summary');
        }

        return $this->redirectToRoute('complaint_additional_information');
    }
}
