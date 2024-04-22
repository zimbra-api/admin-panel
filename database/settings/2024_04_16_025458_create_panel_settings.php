<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('panel.id', 'admin');
        $this->migrator->add('panel.path', 'admin');
        $this->migrator->add('panel.favicon', '');
        $this->migrator->add('panel.spaMode', false);
        $this->migrator->add('panel.brandName', '');
        $this->migrator->add('panel.brandLogo', '');
    }
};
