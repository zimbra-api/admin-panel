<?php declare(strict_types=1);
/**
 * This file is part of the Zimbra Multi-Tenancy Admin Panel project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Filament\Pages;

use App\Settings\ZimbraSettings;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;

/**
 * Zimbra settings page
 *
 * @package  App
 * @category Filament
 * @author   Nguyen Van Nguyen - nguyennv1981@gmail.com
 */
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

    protected function getHeaderActions(): array
    {
        return [
            Action::make('test_connection')
                ->label(__('Test Connection')),
        ];
    }
}
