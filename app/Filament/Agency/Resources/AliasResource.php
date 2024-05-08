<?php declare(strict_types=1);
/**
 * This file is part of the Zimbra Multi-Tenancy Admin Panel project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Filament\Agency\Resources;

use App\Filament\Agency\Resources\AliasResource\Pages;
use App\Models\Alias;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class AliasResource extends Resource
{
    protected static ?string $model = Alias::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Manage';
    protected static ?string $slug = 'alias';

    public static function form(Form $form): Form
    {
        return $form->schema([
            //
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            //
        ])->actions([
            Tables\Actions\EditAction::make(),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAliases::route('/'),
            'create' => Pages\CreateAlias::route('/create'),
            'edit' => Pages\EditAlias::route('/{record}/edit'),
        ];
    }
}
