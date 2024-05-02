<?php declare(strict_types=1);
/**
 * This file is part of the Zimbra Multi-Tenancy Admin Panel project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Filament\Resources\MailHostResource\Pages;

use App\Filament\Resources\MailHostResource;
use App\Support\ZimbraAdminClient;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMailHosts extends ListRecords
{
    protected static string $resource = MailHostResource::class;

    protected function getHeaderActions(): array
    {
        /// zimbraCreateTimestamp
        return [
            Actions\Action::make('pull')
                ->action(function () {
                    $model = static::getResource()::getModel();
                    $client = ZimbraAdminClient::fromSettings();
                    $client->authFromSession();
                    $servers = $client->getAllMailboxServers();
                    foreach ($servers as $server) {
                        $model::where([
                            'zimbra_id' => $server->getId(),
                        ])->firstOr(fn () => $model::create([
                            'zimbra_id' => $server->getId(),
                            'name' => $server->getName(),
                        ]));
                    }
                })
                ->label(__('Pull Data')),
        ];
    }
}
