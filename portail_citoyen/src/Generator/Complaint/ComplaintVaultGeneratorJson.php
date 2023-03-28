<?php

declare(strict_types=1);

namespace App\Generator\Complaint;

use App\Session\ComplaintModel;
use Symfony\Component\Serializer\SerializerInterface;

class ComplaintVaultGeneratorJson implements ComplaintVaultGeneratorInterface
{
    public function __construct(private readonly SerializerInterface $serializer)
    {
    }

    public function generate(ComplaintModel $complaint): string
    {
        return $this->serializer->serialize($complaint, 'json', [
            'complaint_generator' => true,
        ]);
    }
}
