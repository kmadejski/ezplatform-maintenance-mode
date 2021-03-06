# Ibexa DXP Maintenance Mode Bundle
This Ibexa DXP v3.x+ bundle provides a simple way to switch the whole site or selected SiteAccess to maintenance mode. 

In this mode the site (or particular SiteAccess) will not be available for the end-users. Selected template is displayed instead.

# Installation

- Install the bundle:
```bash
composer require kmadejski/ezplatform-maintenance-mode
```

- If you do not use Symfony Flex, you have to enable the bundle manually in your `config/bundles.php` file:
```php
    ...
    EzSystems\EzPlatformMaintenanceModeBundle\EzSystemsEzPlatformMaintenanceModeBundle::class => ['all' => true]
```

- Clear application cache:
```bash
php bin/console cache:clear --env=prod
```

# Configuration

Bundles configuratiton is SiteAccess-aware, therefore all options are configurable in `ezplatform.yml` under `default` SiteAccess configuration key (if you want to switch the whole site into maintenance mode) or under selected SiteAccess:
```yaml
ezplatform:
    system:
        default:
            maintenance_mode:
                enabled: true
```

By default `503` HTTP response code is returned and a default template `@EzSystemsEzPlatformMaintenanceMode/maintenance.html.twig` is rendered. No IP addresses are allowed to visit the page. To modify this behaviour you can add an additional configuration as following example presents:
```yaml
ezplatformgs:
    system:
        default:
            maintenance_mode:
                enabled: true
                allowed_ips: ['::1', '10.0.0.1', '192.168.0.0/16']
                response_code: 404
                template: '@Acme/custom_maintenance.html.twig'
```