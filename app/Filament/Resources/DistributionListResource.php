<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DistributionListResource\Pages;
use App\Filament\Resources\DistributionListResource\RelationManagers;
use App\Models\DistributionList;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DistributionListResource extends Resource
{
    protected static ?string $model = DistributionList::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListDistributionLists::route('/'),
            'create' => Pages\CreateDistributionList::route('/create'),
            'edit' => Pages\EditDistributionList::route('/{record}/edit'),
        ];
    }
}
