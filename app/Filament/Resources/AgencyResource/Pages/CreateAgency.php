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
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateAgency extends CreateRecord
{
    protected static string $resource = AgencyResource::class;
    protected static bool $canCreateAnother = false;

    protected function handleRecordCreation(array $data): Model
    {
        $record = parent::handleRecordCreation($data);

        foreach ($data['coses'] as $cos_id) {
            AgencyCos::create([
                'agency_id' => $record->id,
                'cos_id' => $cos_id,
            ]);
        }

        foreach ($data['mail_hosts'] as $mail_host_id) {
            AgencyMailHost::create([
                'agency_id' => $record->id,
                'mail_host_id' => $mail_host_id,
            ]);
        }

        return $record;
    }

    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? $this->getResource()::getUrl();
    }
}
