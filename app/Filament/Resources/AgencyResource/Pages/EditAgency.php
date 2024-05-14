<?php declare(strict_types=1);
/**
 * This file is part of the Zimbra Multi-Tenancy Admin Panel project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Filament\Resources\AgencyResource\Pages;

use App\Filament\Resources\AgencyResource;
use App\Models\AgencyCos;
use App\Models\AgencyMailHost;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditAgency extends EditRecord
{
    protected static string $resource = AgencyResource::class;

    public function getRelationManagers(): array
    {
        return [];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['coses'] = [];
        foreach ($this->getRecord()->coses as $cos) {
            $data['coses'][] = $cos->id;
        }

        $data['mail_hosts'] = [];
        foreach ($this->getRecord()->mailHosts as $host) {
            $data['mail_hosts'][] = $host->id;
        }

        return $data;
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        AgencyCos::where('agency_id', $record->id)
            ->whereNotIn('cos_id', [
                0,
                ...$data['coses'],
            ])
            ->delete();
        foreach ($data['coses'] as $cos_id) {
            AgencyCos::firstOrCreate([
                'agency_id' => $record->id,
                'cos_id' => $cos_id,
            ]);
        }

        AgencyMailHost::where('agency_id', $record->id)
            ->whereNotIn('mail_host_id', [
                0,
                ...$data['mail_hosts'],
            ])
            ->delete();
        foreach ($data['mail_hosts'] as $mail_host_id) {
            AgencyMailHost::firstOrCreate([
                'agency_id' => $record->id,
                'mail_host_id' => $mail_host_id,
            ]);
        }

        return parent::handleRecordUpdate($record, $data);
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? $this->getResource()::getUrl();
    }
}
