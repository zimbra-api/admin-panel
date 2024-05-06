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
use Filament\Resources\Pages\EditRecord;
use Zimbra\Admin\Struct\Attr;

class EditClassOfService extends EditRecord
{
    protected static string $resource = ClassOfServiceResource::class;

    private static $defaultCoses = [
        'default',
        'defaultExternal',
    ];

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['mail_quota'] = round(
            intval($data['mail_quota']) / static::getResource()::MB, 1
        );
        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $mailQuota = $data['mail_quota'] * static::getResource()::MB;
        $client = app(ZimbraAdminClient::class);

        if (!in_array($data['name'], self::$defaultCoses)) {
            $client->renameCos($data['zimbra_id'], $data['name']);
        }
        $client->modifyCos($data['zimbra_id'], [
            new Attr('zimbraMailQuota', (string) $mailQuota),
            new Attr('description', $data['description']),
        ]);

        $data['mail_quota'] = $mailQuota;
        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl();
    }
}
