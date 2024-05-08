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
use Filament\Forms\{
    Form,
    Get,
};
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
use Illuminate\Support\Str;

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
                TextInput::make('name')
                    ->rules([
                        fn () => function (string $attribute, $value, \Closure $fail) {
                            if (!filter_var($value, FILTER_VALIDATE_DOMAIN)) {
                                $fail(__('The domain name is invalid.'));
                            }
                        },
                    ])
                    ->required()->unique()->label(__('Name')),
                TextInput::make('domain_admin')->email()
                    ->rules([
                        fn (Get $get) => function (
                            string $attribute, $value, \Closure $fail
                        ) use ($get) {
                            if (!Str::endsWith($value, $get('name'))) {
                                $fail(__('The email address must match the domain name.'));
                            }
                        },
                    ])
                    ->required()->label(__('Domain Admin')),
                TextInput::make('admin_password')->password()
                    ->required()->label(__('Admin Password')),
            ]),
            Grid::make(3)->schema([
                Select::make('status')->options(DomainStatus::class)
                    ->required()->label(__('Status')),
                Select::make('coses')
                    ->options(
                        auth()->user()->agency->coses->pluck('name', 'id')
                    )->live()->multiple()
                    ->required()->label(__('Class Of Services')),
                TextInput::make('max_accounts')->numeric()
                    ->default(0)->minValue(0)
                    ->required()->label(__('Max Accounts')),
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
