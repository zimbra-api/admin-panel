<?php declare(strict_types=1);
/**
 * This file is part of the Zimbra Multi-Tenancy Admin Panel project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Filament\Resources;

use App\Filament\Resources\AgencyResource\Pages;
use App\Filament\Resources\AgencyResource\RelationManagers;
use App\Models\Agency;
use App\Models\ClassOfService;
use App\Models\MailHost;
use Filament\Forms\Form;
use Filament\Forms\Components\{
    Select,
    Textarea,
    TextInput,
};
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;

class AgencyResource extends Resource
{
    protected static ?string $model = Agency::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Configure';

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('name')->required()->label(__('Name')),
            TextInput::make('email')->required()->email()->label(__('Email')),
            TextInput::make('telephone')->label(__('Telephone')),
            TextInput::make('mobile')->label(__('Mobile')),
            TextInput::make('address')->label(__('Address')),
            TextInput::make('organization')->label(__('Organization')),
            Select::make('coses')->multiple()->required()
                ->options(ClassOfService::all()->pluck('name', 'id'))
                ->label(__('Class Of Services')),
            Select::make('mail_hosts')->multiple()->required()
                ->options(MailHost::all()->pluck('name', 'id'))
                ->label(__('Mail Hosts')),
            Textarea::make('description')->columnSpan(2)->label(__('Description')),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('name')->searchable()->label(__('Name')),
            TextColumn::make('email')->searchable()->label(__('Email')),
            TextColumn::make('description')->searchable()->label(__('Description')),
        ])
        ->actions([
            EditAction::make(),
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
            'index' => Pages\ListAgencies::route('/'),
            'create' => Pages\CreateAgency::route('/create'),
            'edit' => Pages\EditAgency::route('/{record}/edit'),
        ];
    }
}
