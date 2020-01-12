<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformMaintenanceModeBundle\Event\Subscriber;

use eZ\Publish\Core\MVC\ConfigResolverInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\IpUtils;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Twig\Environment;

final class MaintenanceModeSubscriber implements EventSubscriberInterface
{
    /**
     * @var \eZ\Publish\Core\MVC\ConfigResolverInterface
     */
    private $configResolver;

    /**
     * @var \Twig\Environment
     */
    private $twigEnvironment;

    /**
     * @var string
     */
    private $kernelEnvironment;

    public function __construct(ConfigResolverInterface $configResolver, Environment $twigEnvironment, string $kernelEnvironment)
    {
        $this->configResolver = $configResolver;
        $this->twigEnvironment = $twigEnvironment;
        $this->kernelEnvironment = $kernelEnvironment;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => ['onKernelRequest', 44],
        ];
    }

    public function onKernelRequest(GetResponseEvent $event): void
    {
        if ($this->kernelEnvironment === 'dev') {
            return;
        }

        $request = $event->getRequest();

        /** @var \eZ\Publish\Core\MVC\Symfony\SiteAccess $siteAccess */
        $siteAccess = $request->get('siteaccess');

        if (!$this->configResolver->getParameter('maintenance_mode.enabled')) {
            return;
        }

        $allowedIps = $this->configResolver->getParameter('maintenance_mode.allowed_ips');

        if (IpUtils::checkIp($request->getClientIp(), $allowedIps)) {
            return;
        }

        $template = $this->configResolver->getParameter('maintenance_mode.template', null, $siteAccess->name);
        $responseCode = $this->configResolver->getParameter('maintenance_mode.response_code', null, $siteAccess->name);

        $event->setResponse(
            new Response(
                $this->twigEnvironment->render($template ?? '@EzSystemsEzPlatformMaintenanceMode/maintenance.html.twig'),
                $responseCode
            )
        );

        $event->stopPropagation();
    }
}
