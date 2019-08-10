<?php

namespace EzSystems\EzPlatformMaintenanceModeBundle\EventListener;

use eZ\Publish\Core\MVC\ConfigResolverInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Twig\Environment;

final class MaintenanceModeListener
{
    /**
     * @var \eZ\Publish\Core\MVC\ConfigResolverInterface
     */
    private $configResolver;

    /**
     * @var \Twig\Environment
     */
    private $environment;

    public function __construct(ConfigResolverInterface $configResolver, Environment $environment)
    {
        $this->configResolver = $configResolver;
        $this->environment = $environment;
    }

    public function onKernelRequest(GetResponseEvent $event): void
    {
        $request = $event->getRequest();
        /** @var \eZ\Publish\Core\MVC\Symfony\SiteAccess $siteAccess */
        $siteAccess = $request->get('siteaccess');

        if (!$this->configResolver->getParameter('enabled', 'maintenance_mode', $siteAccess->name)) {
            return;
        }

        $allowedIps = $this->configResolver->getParameter('allowed_ips', 'maintenance_mode', $siteAccess->name);

        if (!\in_array($request->getClientIp(), $allowedIps, true)) {
            return;
        }

        $template = $this->configResolver->getParameter('template', 'maintenance_mode', $siteAccess->name);
        $responseCode = $this->configResolver->getParameter('response_code', 'maintenance_mode', $siteAccess->name);

        $event->setResponse(
            new Response(
                $this->environment->render($template ?? '@EzSystemsEzPlatformMaintenanceMode/maintenance.html.twig'),
                $responseCode
            )
        );
        $event->stopPropagation();
    }
}