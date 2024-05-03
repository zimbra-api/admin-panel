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
use Illuminate\Support\Carbon;

class ListMailHosts extends ListRecords
{
    protected static string $resource = MailHostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('sync')
                ->action(function () {
                    $model = static::getResource()::getModel();
                    $client = ZimbraAdminClient::fromSettings();
                    $client->authFromSession();
                    $servers = $client->getAllMailboxServers();
                    foreach ($servers as $server) {
                        $zimbraCreate = ZimbraAdminClient::getAttr(
                            $server, 'zimbraCreateTimestamp'
                        );
                        $model::where([
                            'zimbra_id' => $server->getId(),
                        ])->firstOr(fn () => $model::create([
                            'zimbra_id' => $server->getId(),
                            'name' => $server->getName(),
                            'attributes' => ZimbraAdminClient::getAttrs($server),
                            'zimbra_create' => $zimbraCreate ? Carbon::createFromTimestamp(strtotime(
                                intval($zimbraCreate) . 'Z'
                            )) : null,
                        ]));
                    }

                    redirect(static::getResource()::getUrl());
                })
                ->label(__('Sync')),
        ];
    }
}
