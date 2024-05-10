<?php

declare(strict_types=1);

namespace App\Security;

use Ibexa\Bundle\Core\DependencyInjection\Configuration\ConfigBuilderInterface;
use Ibexa\Bundle\Core\DependencyInjection\Security\PolicyProvider\PolicyProviderInterface;

final class PolicyProvider implements PolicyProviderInterface
{
    public function addPolicies(ConfigBuilderInterface $configBuilder): void
    {
        $configBuilder->addConfig(
            [
                'ngsite_logger' => [
                    'view_logs' => null,
                ],
            ],
        );
    }
}
