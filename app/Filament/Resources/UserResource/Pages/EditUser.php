<?php declare(strict_types=1);
/**
 * This file is part of the Zimbra Multi-Tenancy Admin Panel project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Filament\Resources\UserResource\Pages;

use App\Enums\UserRole;
use App\Filament\Resources\UserResource;
use Filament\Forms\Form;
use Filament\Forms\Components\{
    Select,
    TextInput,
};
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

/**
 * Edit user record page
 *
 * @package  App
 * @category Filament
 * @author   Nguyen Van Nguyen - nguyennv1981@gmail.com
 */
class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    public function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('name')->required()->label(__('Name')),
            TextInput::make('email')->readonly()->label(__('Email Address')),
            TextInput::make('password')->password()
                ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                ->dehydrated(fn ($state) => filled($state))
                ->required(false)->label(__('Password')),
            Select::make('role')->options(
                UserRole::class
            )->hidden(
                $this->record->isSupperAdmin()
            )->required()->label(__('Role')),
        ]);
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['role'] = $this->record->getRoleNames()->first();
        return $data;
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $assignedRole = $record->getRoleNames()->first();
        $role = $data['role'] ?? '';
        if (empty($assignedRole)) {
           $record->assignRole($role);
        }
        elseif (!empty($role) && $role !== $assignedRole) {
           $record->removeRole($assignedRole);
           $record->assignRole($role);
        }
        return parent::handleRecordUpdate($record, $data);
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return __('User has been saved!');
    }

    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? $this->getResource()::getUrl();
    }
}
