<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace EzSystems\EzPlatformMaintenanceModeBundle\DependencyInjection;

use eZ\Bundle\EzPublishCoreBundle\DependencyInjection\Configuration\SiteAccessAware\Configuration as SiteAccessConfiguration;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\HttpFoundation\Response;

final class Configuration extends SiteAccessConfiguration
{
    /**
     * Generates the configuration tree builder.
     *
     * @return \Symfony\Component\Config\Definition\Builder\TreeBuilder The tree builder
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('maintenance_mode');
        $systemNode = $this->generateScopeBaseNode($rootNode);
        $systemNode
            ->booleanNode('enabled')->end()
            ->arrayNode('allowed_ips')
                ->prototype('scalar')->end()
            ->end()
            ->scalarNode('response_code')
                ->defaultValue(Response::HTTP_SERVICE_UNAVAILABLE)
            ->end()
            ->scalarNode('template')
                ->defaultValue(null)
            ->end()
        ->end();

        return $treeBuilder;
    }
}
