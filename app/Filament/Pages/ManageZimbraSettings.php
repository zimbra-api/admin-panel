<?php declare(strict_types=1);
/**
 * This file is part of the Zimbra Multi-Tenancy Admin Panel project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Filament\Pages;

use App\Settings\ZimbraSettings;
use App\Support\ZimbraAdminClient;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\SettingsPage;
use Filament\Support\Exceptions\Halt;

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
    protected static ?string $navigationGroup = 'Configure';

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
            TextInput::make('defaultDomain')
                ->required()->label(__('Default Domain')),
        ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('test_connection')->action(
                fn () => $this->testConnection()
            )->label(__('Test Connection')),
        ];
    }

    public function afterValidate()
    {
        if (!$this->testConnection(false)) {
            throw (new Halt())->rollBackDatabaseTransaction(false);
        }
    }

    private function testConnection(bool $notifySuccess = true): bool
    {
        try {
            $data = $this->form->getState();
            $client = new ZimbraAdminClient($data['serviceUrl']);
            $client->auth(
                $data['adminUser'], $data['adminPassword']
            );
            if (!empty($defaultDomain = $data['defaultDomain'])) {
                $domains = $client->getConfigByName('zimbraDefaultDomainName');
                if (!in_array($defaultDomain, $domains)) {
                    Notification::make()
                        ->warning()
                        ->title($defaultDomain . ' ' . __('is not default domain!'))
                        ->send();
                    return false;
                }
            }
            if ($notifySuccess) {
                Notification::make()
                    ->success()
                    ->title(__('Connection to zimbra service success!'))
                    ->send();
            }
            return true;
        }
        catch (\Throwable $t) {
            logger()->error($t);
            Notification::make()
                ->warning()
                ->title(__('Connection to zimbra service failed!'))
                ->body($t->getMessage())
                ->send();
            return false;
        }
    }
}
