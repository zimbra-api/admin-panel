<?php declare(strict_types=1);
/**
 * This file is part of the Zimbra Multi-Tenancy Admin Panel project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Filament\Agency\Resources;

use App\Filament\Agency\Resources\DomainResource\Pages;
use App\Models\Domain;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;

class DomainResource extends Resource
{
    protected static ?string $model = Domain::class;
    protected static ?string $navigationIcon = 'heroicon-m-globe-alt';
    protected static ?string $navigationGroup = 'Manage';
    protected static ?string $slug = 'domain';

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('name')->searchable()->label(__('Name')),
            TextColumn::make('domain_admin')->searchable()->label(__('Domain Admin')),
            TextColumn::make('status')->label(__('Status')),
            TextColumn::make('coses.name')->listWithLineBreaks()->label(__('Class Of Services')),
        ])->actions([
            EditAction::make(),
        ]);
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
