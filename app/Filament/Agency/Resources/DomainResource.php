<?php declare(strict_types=1);
/**
 * This file is part of the Zimbra Multi-Tenancy Admin Panel project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Filament\Agency\Resources;

use App\Filament\Agency\Resources\DomainResource\Pages;
use App\Filament\Agency\Resources\DomainResource\RelationManagers;
use App\Enums\DomainStatus;
use App\Models\Domain;
use Filament\Forms\Form;
use Filament\Forms\Components\{
    Grid,
    Hidden,
    Select,
    Textarea,
    TextInput,
};
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class DomainResource extends Resource
{
    protected static ?string $model = Domain::class;
    protected static ?string $navigationIcon = 'heroicon-m-globe-alt';
    protected static ?string $navigationGroup = 'Manage';
    protected static ?string $slug = 'domain';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Grid::make(3)->schema([
                TextInput::make('name')->required()->label(__('Name')),
                TextInput::make('domain_admin')->required()->email()->label(__('Domain Admin')),
                TextInput::make('admin_password')->required()->password()->label(__('Admin Password')),
            ]),
            Grid::make(3)->schema([
                Select::make('status')->required()
                    ->options(DomainStatus::class)
                    ->label(__('Status')),
                Select::make('coses')->required()
                    ->options(
                        auth()->user()->agency->coses->pluck('name', 'id')
                    )->multiple()
                    ->label(__('Class Of Services')),
                TextInput::make('max_accounts')->required()
                    ->numeric()
                    ->label(__('Max Accounts')),
            ]),
            Textarea::make('description')->columnSpan(2)
                ->label(__('Description')),
            Hidden::make('agency_id')->default(auth()->user()->agency->id),
            Hidden::make('zimbra_id'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            //
        ])
        ->actions([
            Tables\Actions\EditAction::make(),
        ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDomains::route('/'),
            'create' => Pages\CreateDomain::route('/create'),
            'edit' => Pages\EditDomain::route('/{record}/edit'),
        ];
    }
}
