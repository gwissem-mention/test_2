<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File as HttpFile;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity]
#[Vich\Uploadable]
class File
{
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    private ?int $id = null;

    #[Vich\UploadableField(mapping: 'files', fileNameProperty: 'fileName')]
    private ?HttpFile $file = null;

    #[ORM\Column(type: 'string')]
    private ?string $fileName = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFile(): ?HttpFile
    {
        return $this->file;
    }

    public function setFile(?HttpFile $file): static
    {
        $this->file = $file;

        return $this;
    }

    public function getFileName(): ?string
    {
        return $this->fileName;
    }

    public function setFileName(?string $fileName): static
    {
        $this->fileName = $fileName;

        return $this;
    }
}
