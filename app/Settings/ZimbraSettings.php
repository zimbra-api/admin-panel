<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class ZimbraSettings extends Settings
{
    public string $serviceUrl;

    public string $adminUser;

    public string $adminPassword;

    public static function group(): string
    {
        return 'zimbra';
    }

    public static function encrypted(): array
    {
        return [
            'adminPassword',
        ];
    }
}
