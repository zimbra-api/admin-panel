<?php declare(strict_types=1);
/**
 * This file is part of the Zimbra Multi-Tenancy Admin Panel project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Filament\Resources\AgencyResource\RelationManagers;

use App\Models\AgencyMember;
use App\Models\User;
use Filament\Forms\Form;
use Filament\Forms\Components\Select;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Columns\TextColumn;

class MembersRelationManager extends RelationManager
{
    protected static string $relationship = 'members';

    public function form(Form $form): Form
    {
        return $form->schema([
            Select::make('user')
                ->required()->multiple()
                ->options(
                    User::all()->except(
                        AgencyMember::all()->map(
                            fn (AgencyMember $member) => $member->user_id
                        )->toArray()
                    )->pluck('name', 'id')
                ),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('name'),
                TextColumn::make('email'),
            ])
            ->headerActions([
                CreateAction::make()
                    ->createAnother(false)
                    ->modalSubmitActionLabel(__('Assign'))
                    ->modalHeading(__('Assign Members'))
                    ->label(__('Assign Members')),
            ])
            ->actions([
                DeleteAction::make()
                    ->label(__('Remove Member')),
            ])
            ->emptyStateHeading(__('No members yet'))
            ->emptyStateDescription(__('Assign a member to get started.'));
    }
}
