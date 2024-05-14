<?php declare(strict_types=1);
/**
 * This file is part of the Zimbra Multi-Tenancy Admin Panel project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Filament\Agency\Resources\DomainResource\Pages;

use App\Filament\Agency\Resources\DomainResource;
use App\Models\ClassOfService;
use App\Models\DomainCos;
use Filament\Forms\{
    Form,
    Get,
    Set,
};
use Filament\Forms\Components\{
    Grid,
    Hidden,
    Select,
    Textarea,
    TextInput,
};
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditDomain extends EditRecord
{
    protected static string $resource = DomainResource::class;

    public function form(Form $form): Form
    {
        return $form->schema([
            Grid::make(2)->schema([
                TextInput::make('name')
                    ->readonly()->label(__('Name')),
                TextInput::make('domain_admin')->email()
                    ->readonly()->label(__('Domain Admin')),
            ]),
            Grid::make(3)->schema([
                Select::make('status')->options(DomainStatus::class)
                    ->required()->label(__('Status')),
                Select::make('coses')
                    ->options(
                        auth()->user()->agency->coses->pluck('name', 'id')
                    )->live()->multiple()
                    ->afterStateUpdated(function (Set $set, array $state) {
                        $set(
                            'max_accounts',
                            ClassOfService::find($state)->sum('max_accounts')
                        );
                    })
                    ->default(
                        DomainCos::where('domain_id', $this->getRecord()->id)
                            ->get()->pluck('id', 'id')->toArray()
                    )
                    ->required()->label(__('Class Of Services')),
                TextInput::make('max_accounts')
                    ->minValue(0)->readonly()
                    ->required()->label(__('Max Accounts')),
            ]),
            Textarea::make('description')->columnSpan(2)
                ->label(__('Description')),
            Hidden::make('agency_id'),
            Hidden::make('zimbra_id'),
        ]);
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        DomainCos::where('domain_id', $record->id)
            ->whereNotIn('cos_id', [
                0,
                ...$data['coses'],
            ])
            ->delete();
        foreach ($data['coses'] as $cos_id) {
            DomainCos::firstOrCreate([
                'domain_id' => $record->id,
                'cos_id' => $cos_id,
            ]);
        }
        return parent::handleRecordUpdate($record, $data);
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? $this->getResource()::getUrl();
    }
}
