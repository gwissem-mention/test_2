<?php

declare(strict_types=1);

namespace App\Form\Validator;

use Symfony\Component\Form\Form;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class EtalabAddressValidator
{
    public function validate(?string $value, ExecutionContextInterface $context): void
    {
        if (null === $value) {
            return;
        }

        /** @var Form $form */
        $form = $context->getObject();
        /** @var Form $formParent */
        $formParent = $form->getParent();
        /** @var string $selectionId */
        $selectionId = $formParent->get('selectionId')->getData();

        if (empty($selectionId)) {
            $context->addViolation('Veuillez sélectionner une des adresses proposées sous le champ pour continuer.');
        }
    }
}
