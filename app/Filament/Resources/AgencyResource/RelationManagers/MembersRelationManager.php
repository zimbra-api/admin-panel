<?php declare(strict_types=1);
/**
 * This file is part of the Zimbra Multi-Tenancy Admin Panel project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Filament\Resources\AgencyResource\RelationManagers;

use App\Enums\UserRole;
use App\Models\AgencyMember;
use App\Models\User;
use Filament\Forms\Form;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class MembersRelationManager extends RelationManager
{
    protected static string $relationship = 'members';

    public function form(Form $form): Form
    {
        return $form->schema([
            Select::make('users')
                ->required()->multiple()
                ->options(
                    User::whereNotIn('id', [
                        1,
                        ...AgencyMember::all()->map(
                                fn (AgencyMember $member) => $member->user_id
                            )->toArray(),
                        ]
                    )
                    ->withoutRole(UserRole::Administrator)
                    ->get()->pluck('name', 'id')
                ),
            Hidden::make('agency_id')->default($this->ownerRecord->id),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('name'),
            TextColumn::make('email'),
        ])
        ->headerActions([
            CreateAction::make()
                ->createAnother(false)
                ->using(function (array $data): Model {
                    $model = null;
                    foreach ($data['users'] as $user_id) {
                        $model = AgencyMember::firstOrCreate([
                            'agency_id' => $data['agency_id'],
                            'user_id' => $user_id,
                        ]);
                    }
                    return $model ?? new AgencyMember();
                })
                ->modalSubmitActionLabel(__('Assign'))
                ->modalHeading(__('Assign Members'))
                ->successNotificationTitle(__('Member assigned'))
                ->label(__('Assign Members')),
        ])
        ->actions([
            Action::make('remove')
                ->requiresConfirmation()
                ->action(fn (User $user) => AgencyMember::firstWhere([
                    'agency_id' => $this->ownerRecord->id,
                    'user_id' => $user->id,
                ])->delete())
                ->modalHeading(__('Remove Member'))
                ->label(__('Remove Member')),
        ])
        ->emptyStateHeading(__('No members yet'))
        ->emptyStateDescription(__('Assign a member to get started.'));
    }
}
