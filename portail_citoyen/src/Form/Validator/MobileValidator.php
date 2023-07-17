<?php

declare(strict_types=1);

namespace App\Form\Validator;

use libphonenumber\NumberParseException;
use libphonenumber\PhoneNumberType;
use libphonenumber\PhoneNumberUtil;
use Symfony\Component\Form\Form;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class MobileValidator
{
    public function __construct(
        private readonly PhoneNumberUtil $phoneUtil
    ) {
    }

    public function validate(?string $value, ExecutionContextInterface $context): void
    {
        if (null === $value) {
            return;
        }

        /** @var Form $form */
        $form = $context->getObject();
        /** @var Form $formParent */
        $formParent = $form->getParent();
        /** @var string $country */
        $country = $formParent->get('country')->getData();
        try {
            $phone = $this->phoneUtil->parse($value, $country);

            if (!$this->phoneUtil->isValidNumber($phone)) {
                $context->addViolation('pel.phone.is.invalid');
            }

            if (!(PhoneNumberType::MOBILE === $this->phoneUtil->getNumberType($phone))) {
                $context->addViolation('pel.phone.mobile.error');
            }
        } catch (NumberParseException) {
            $context->addViolation('pel.phone.is.invalid');
        }
    }
}
