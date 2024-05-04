<?php declare(strict_types=1);
/**
 * This file is part of the Zimbra Multi-Tenancy Admin Panel project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Filament\Resources;

use App\Filament\Resources\MailHostResource\Pages;
use App\Filament\Resources\MailHostResource\RelationManagers;
use App\Models\MailHost;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;

/**
 * User resource
 *
 * @package  App
 * @category Filament
 * @author   Nguyen Van Nguyen - nguyennv1981@gmail.com
 */
class MailHostResource extends Resource
{
    protected static ?string $model = MailHost::class;
    protected static ?string $navigationIcon = 'heroicon-o-server-stack';
    protected static ?string $navigationGroup = 'Configure';
    protected static ?string $slug = 'mail-host';

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('name')->label(__('Name')),
            TextColumn::make('zimbra_create')->dateTime()->sortable()->label(__('Created At')),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMailHosts::route('/'),
        ];
    }
}
