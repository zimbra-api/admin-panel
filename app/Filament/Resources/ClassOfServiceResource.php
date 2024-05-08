<?php declare(strict_types=1);
/**
 * This file is part of the Zimbra Multi-Tenancy Admin Panel project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Filament\Resources;

use App\Filament\Resources\ClassOfServiceResource\Pages;
use App\Models\ClassOfService;
use Filament\Forms\Form;
use Filament\Forms\Components\{
    Grid,
    Hidden,
    Textarea,
    TextInput,
};
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;

/**
 * Class of service resource
 *
 * @package  App
 * @category Filament
 * @author   Nguyen Van Nguyen - nguyennv1981@gmail.com
 */
class ClassOfServiceResource extends Resource
{
    const MB = 1048576;

    protected static ?string $model = ClassOfService::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationGroup = 'Configure';
    protected static ?string $slug = 'cos';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Grid::make(3)->schema([
                TextInput::make('name')
                    ->required()->label(__('Name')),
                TextInput::make('mail_quota')->numeric()
                    ->default(0)->minValue(0)
                    ->required()->label(__('Mail Quota (MB)')),
                TextInput::make('max_accounts')->numeric()
                    ->default(0)->minValue(0)
                    ->required()->label(__('Max Accounts')),
            ]),
            Textarea::make('description')->columnSpan(2)
                ->label(__('Description')),
            Hidden::make('zimbra_id'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('name')->searchable()->label(__('Name')),
            TextColumn::make('description')->searchable()
                ->label(__('Description')),
            TextColumn::make('mail_quota')->state(
                fn ($record) => round(intval($record->mail_quota) / self::MB, 1)
            )->label(__('Mail Quota (MB)')),
            TextColumn::make('max_accounts')->label(__('Max Accounts')),
        ])->actions([
            EditAction::make(),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListClassOfServices::route('/'),
            'create' => Pages\CreateClassOfService::route('/create'),
            'edit'   => Pages\EditClassOfService::route('/{record}/edit'),
        ];
    }
}
