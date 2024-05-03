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
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Carbon;

class ListClassOfServices extends ListRecords
{
    protected static string $resource = ClassOfServiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label(__('New COS')),
            Actions\Action::make('sync')
                ->action(function () {
                    $model = static::getResource()::getModel();
                    $client = ZimbraAdminClient::fromSettings();
                    $client->authFromSession();
                    $coses = $client->getAllCos()->getCosList();
                    foreach ($coses as $cos) {
                        $zimbraCreate = ZimbraAdminClient::getAttr(
                            $cos, 'zimbraCreateTimestamp'
                        );
                        $model::where([
                            'zimbra_id' => $cos->getId(),
                        ])->firstOr(fn () => $model::create([
                            'zimbra_id' => $cos->getId(),
                            'name' => $cos->getName(),
                            'mail_quota' => (int) ZimbraAdminClient::getAttr($cos, 'zimbraMailQuota'),
                            'description' => ZimbraAdminClient::getAttr($cos, 'description'),
                            'attributes' => ZimbraAdminClient::getAttrs($cos),
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
