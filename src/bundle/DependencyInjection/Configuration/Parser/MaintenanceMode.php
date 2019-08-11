<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformMaintenanceModeBundle\DependencyInjection\Configuration\Parser;

use eZ\Bundle\EzPublishCoreBundle\DependencyInjection\Configuration\AbstractParser;
use eZ\Bundle\EzPublishCoreBundle\DependencyInjection\Configuration\SiteAccessAware\ContextualizerInterface;
use Symfony\Component\Config\Definition\Builder\NodeBuilder;
use Symfony\Component\HttpFoundation\Response;

final class MaintenanceMode extends AbstractParser
{
    public function addSemanticConfig(NodeBuilder $nodeBuilder): void
    {
        $nodeBuilder
            ->arrayNode('maintenance_mode')
                ->info('Maintenance Mode configuration')
                ->children()
                    ->booleanNode('enabled')
                        ->defaultValue(false)
                    ->end()
                    ->arrayNode('allowed_ips')
                        ->scalarPrototype()->end()
                        ->defaultValue([])
                    ->end()
                    ->scalarNode('response_code')
                        ->defaultValue(Response::HTTP_SERVICE_UNAVAILABLE)
                    ->end()
                    ->scalarNode('template')
                        ->defaultValue(null)
                    ->end()
                ->end()
            ->end();
    }

    public function mapConfig(array &$scopeSettings, $currentScope, ContextualizerInterface $contextualizer): void
    {
        if (empty($scopeSettings['maintenance_mode'])) {
            return;
        }

        $settings = $scopeSettings['maintenance_mode'];

        if (!empty($settings['enabled'])) {
            $contextualizer->setContextualParameter(
                'maintenance_mode.enabled',
                $currentScope,
                $settings['enabled']
            );
        }

        if (!empty($settings['allowed_ips'])) {
            $contextualizer->setContextualParameter(
                'maintenance_mode.allowed_ips',
                $currentScope,
                $settings['allowed_ips'] ?? []
            );
        }

        if (!empty($settings['response_code'])) {
            $contextualizer->setContextualParameter(
                'maintenance_mode.response_code',
                $currentScope,
                $settings['response_code']
            );
        }

        if (!empty($settings['template'])) {
            $contextualizer->setContextualParameter(
                'maintenance_mode.template',
                $currentScope,
                $settings['template']
            );
        }
    }

    public function postMap(array $config, ContextualizerInterface $contextualizer)
    {
        $contextualizer->mapConfigArray('maintenance_mode.allowed_ips', $config);
    }
}
