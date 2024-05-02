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
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MailHostResource extends Resource
{
    protected static ?string $model = MailHost::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Settings';

    public static function table(Table $table): Table
    {
        return $table->columns([
            //
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMailHosts::route('/'),
        ];
    }
}
