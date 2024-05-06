<?php declare(strict_types=1);
/**
 * This file is part of the Zimbra Multi-Tenancy Admin Panel project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Filament\Resources\ClassOfServiceResource\Pages;

use App\Filament\Resources\ClassOfServiceResource;
use App\Support\ZimbraAdminClient;
use Filament\Resources\Pages\CreateRecord;
use Zimbra\Admin\Struct\Attr;

class CreateClassOfService extends CreateRecord
{
    protected static string $resource = ClassOfServiceResource::class;
    protected static bool $canCreateAnother = false;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $mailQuota = $data['mail_quota'] * static::getResource()::MB;
        $client = app(ZimbraAdminClient::class);
        $cos = $client->createCos($data['name'], [
            new Attr('zimbraMailQuota', (string) $mailQuota),
            new Attr('description', $data['description']),
        ])->getCos();

        $data['zimbra_id'] = $cos->getId();
        $data['mail_quota'] = $mailQuota;
        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl();
    }
}
