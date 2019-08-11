# eZ Platform Maintenance Mode Bundle
This eZ Platform v2.5+ bundle provides a simple way to switch the whole site or selected SiteAccess to maintenance mode. 

In this mode the site (or particular SiteAccess) will not be available for the end-users. Selected template is displayed instead.

# Installation

1) Since the bundle is not on packagist yet, you have to add the repository manually to your `composer.json` file:
```json
    "repositories": [
        { "type": "composer", "url": "https://updates.ez.no/ttl" },
        { "type": "vcs", "url": "https://github.com/kmadejski/ezplatform-maintenance-mode.git" }
    ],
```

2) Install the bundle:
```bash
composer require kmadejski/ezplatform-maintenance-mode:dev-master
```

3) Enable the bundle in your `app/AppKernel.php` file:
```php
    $bundles = [
        ...
        new EzSystems\EzPlatformMaintenanceModeBundle\EzSystemsEzPlatformMaintenanceModeBundle(),
    ];
```

4) Clear application cache:
```bash
php bin/console cache:clear 
```

# Configuration

Bundles configuratiton is SiteAccess-aware, therefore all options are configurable in `ezplatform.yml` under `default` SiteAccess configuration key (if you want to switch the whole site into maintenance mode) or under selected SiteAccess:
```yaml
ezpublish:
    system:
        default:
            maintenance_mode:
                enabled: true
```

By default `503` HTTP response code is returned and a default template `@EzSystemsEzPlatformMaintenanceMode/maintenance.html.twig` is rendered. No IP addresses are allowed to visit the page. To modify this behaviour you can add an additional configuration as following example presents:
```yaml
ezpublish:
    system:
        default:
            maintenance_mode:
                enabled: true
                allowed_ips: ['::1', '10.0.0.1']
                response_code: 404
                template: '@Acme/custom_maintenance.html.twig'
```