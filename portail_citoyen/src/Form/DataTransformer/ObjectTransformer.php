<?php

declare(strict_types=1);

namespace App\Form\DataTransformer;

use App\Form\Model\Facts\ObjectModel;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class ObjectTransformer implements DataTransformerInterface
{
    /**
     * @return array<ObjectModel>
     */
    public function transform(mixed $value): array
    {
        if (false === is_array($value)) {
            throw new TransformationFailedException('The value must be an array');
        }

        return array_map(function ($item): ObjectModel {
            $objectModel = new ObjectModel();
            $objectModel->setLabel($item['label'] ?? '');

            return $objectModel;
        }, $value);
    }

    /**
     * @return Collection<int, ObjectModel>
     */
    public function reverseTransform(mixed $value): Collection
    {
        if (false === is_array($value)) {
            throw new TransformationFailedException('The value must be an array');
        }

        return new ArrayCollection($value);
    }
}
