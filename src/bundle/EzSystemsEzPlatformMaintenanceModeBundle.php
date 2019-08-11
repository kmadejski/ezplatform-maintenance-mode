<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace EzSystems\EzPlatformMaintenanceModeBundle;

use EzSystems\EzPlatformMaintenanceModeBundle\DependencyInjection\Configuration\Parser\MaintenanceMode;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

final class EzSystemsEzPlatformMaintenanceModeBundle extends Bundle
{
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        /** @var \eZ\Bundle\EzPublishCoreBundle\DependencyInjection\EzPublishCoreExtension $core */
        $core = $container->getExtension('ezpublish');
        $core->addConfigParser(new MaintenanceMode());
        $core->addDefaultSettings(__DIR__ . '/Resources/config', ['default_settings.yml']);
    }
}
