<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('zimbra.serviceUrl', '');
        $this->migrator->add('zimbra.adminUser', '');
        $this->migrator->addEncrypted('zimbra.adminPassword', '');
    }
};
