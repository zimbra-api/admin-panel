<?php

namespace App\Filament\Pages;

use App\Settings\ZimbraSettings;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;

class ManageZimbraSettings extends SettingsPage
{
    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static ?string $slug = 'zimbra-settings';
    protected static string $settings = ZimbraSettings::class;

    public static function getNavigationLabel(): string
    {
        return __('Zimbra Settings');
    }

    public function getTitle(): string
    {
        return __('Zimbra Settings');
    }

    public function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('serviceUrl')
                ->url()->required()->label(__('Service Url')),
            TextInput::make('adminUser')
                ->required()->label(__('Admin User')),
            TextInput::make('adminPassword')
                ->password()->required()->label(__('Admin Password')),
        ]);
    }
}
