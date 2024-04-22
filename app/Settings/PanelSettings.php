<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class PanelSettings extends Settings
{
    public string $id;
    public string $path;
    public string $favicon;
    public bool $spaMode;
    public string $brandName;
    public string $brandLogo;

    public static function group(): string
    {
        return 'panel';
    }
}
