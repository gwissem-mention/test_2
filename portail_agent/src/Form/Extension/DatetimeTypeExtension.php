<?php

declare(strict_types=1);

namespace App\Form\Extension;

use App\Entity\User;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DatetimeTypeExtension extends AbstractTypeExtension
{
    public function __construct(private readonly Security $security)
    {
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        /** @var User $user */
        $user = $this->security->getUser();
        $resolver->setDefaults([
            'model_timezone' => 'UTC',
            'view_timezone' => $user->getTimezone(),
            'reference_date' => new \DateTime('now', new \DateTimeZone('UTC')),
        ]);
    }

    public static function getExtendedTypes(): iterable
    {
        return [DateType::class, TimeType::class];
    }
}
