<?php

namespace App\Compiler;

use App\Security\FranceConnectListener;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Pass a logout target url map to FranceConnectListener.
 */
class LogoutTargetPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        $logoutTargetPathMap = [];

        $firewalls = $container->getParameter('security.firewalls');

        if (!is_iterable($firewalls)) {
            throw new \LogicException('security.firewalls should contain an iterable value');
        }

        foreach ($firewalls as $firewallName) {
            if ($container->hasDefinition('security.logout.listener.default.'.$firewallName)) {
                $def = $container->getDefinition('security.logout.listener.default.'.$firewallName);
                $logoutTargetPathMap[$firewallName] = $def->getArgument(1);
            }
        }

        $container->getDefinition(FranceConnectListener::class)->setArgument('$logoutTargetPathMap', $logoutTargetPathMap);
    }
}
