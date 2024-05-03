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

        if (!empty($data['coses'])) {
            foreach ($data['coses'] as $id) {
                AgencyCos::create([
                    'agency_id' => $record->id,
                    'cos_id' => $id,
                ]);
            }
        };

        if (!empty($data['mail_hosts'])) {
            foreach ($data['mail_hosts'] as $id) {
                AgencyMailHost::create([
                    'agency_id' => $record->id,
                    'mail_host_id' => $id,
                ]);
            }
        };

        return $record;
    }

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl();
    }
}
