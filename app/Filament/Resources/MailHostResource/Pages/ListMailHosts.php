<?php declare(strict_types=1);
/**
 * This file is part of the Zimbra Multi-Tenancy Admin Panel project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Filament\Resources\MailHostResource\Pages;

use App\Filament\Resources\MailHostResource;
use App\Zimbra\AdminClient;
use Filament\Actions\Action;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Carbon;

class ListMailHosts extends ListRecords
{
    protected static string $resource = MailHostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('sync')->action(function () {
                $model = static::getResource()::getModel();
                $client = app(AdminClient::class);
                $servers = $client->getAllMailboxServers();
                foreach ($servers as $server) {
                    $attrs = AdminClient::getAttrs($server);
                    $zimbraCreate = null;
                    if (!empty($attrs['zimbraCreateTimestamp'])) {
                        $zimbraCreate = Carbon::createFromTimestamp(strtotime(
                            intval($attrs['zimbraCreateTimestamp']) . 'Z'
                        ));
                    }
                    $model::where([
                        'zimbra_id' => $server->getId(),
                    ])->firstOr(fn () => $model::create([
                        'zimbra_id' => $server->getId(),
                        'name' => $server->getName(),
                        'attributes' => $attrs,
                        'zimbra_create' => $zimbraCreate,
                    ]));
                }

                redirect(static::getResource()::getUrl());
            })
            ->label(__('Sync')),
        ];
    }
}
