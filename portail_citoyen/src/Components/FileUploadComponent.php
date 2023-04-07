<?php

declare(strict_types=1);

namespace App\Components;

use Symfony\Component\HttpFoundation\File\File as HttpFoundationFile;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent('file_upload')]
class FileUploadComponent
{
    use DefaultActionTrait;

    #[LiveProp]
    public int $parentIndex = 0;

    #[LiveProp]
    public string $inputId;

    #[LiveProp]
    public string $inputLabel;

    #[LiveProp]
    public string $inputHelp;

    /**
     * @var array<int, array<string, string>>
     */
    #[LiveProp(writable: true)]
    public array $files = [];

    /**
     * @var array<string>
     */
    #[LiveProp(writable: true)]
    public array $errors = [];

    #[LiveAction]
    public function upload(ValidatorInterface $validator, TranslatorInterface $translator): void
    {
        $this->files = [];
        $fileConstraint = new File([
            'mimeTypes' => [
                'image/jpeg',
                'image/png',
                'application/pdf',
            ],
            'mimeTypesMessage' => 'pel.file.must.be.image.or.pdf',
        ]);

        foreach ($_FILES as $uploadedFile) {
            $tmpName = $uploadedFile['tmp_name'];
            $file = new HttpFoundationFile($tmpName);
            $violations = $validator->validate($file, $fileConstraint);

            if ($violations->count() > 0) {
                foreach ($violations as $violation) {
                    $this->errors[] = $translator->trans((string) $violation->getMessage());
                }

                throw new UnprocessableEntityHttpException('Form validation failed in component');
            }

            $newName = sha1(uniqid((string) mt_rand(), true)).'.'.$file->guessExtension();
            move_uploaded_file($tmpName, sys_get_temp_dir().'/'.$newName);

            $name = basename($uploadedFile['name']);
            $this->files[$this->parentIndex][$name] = $newName;
        }
    }
}
