<?php declare(strict_types=1);
/**
 * This file is part of the Zimbra Multi-Tenancy Admin Panel project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Filament\Agency\Resources\DomainResource\Pages;

use App\Filament\Agency\Resources\DomainResource;
use App\Support\ZimbraAdminClient;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Carbon;
use Zimbra\Admin\Struct\Attr;

class CreateDomain extends CreateRecord
{
    protected static string $resource = DomainResource::class;
    protected static bool $canCreateAnother = false;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $client = app(ZimbraAdminClient::class);
        $domain = $client->createDomain($data['name'], [
            new Attr('description', $data['description']),
            new Attr('zimbraDomainMaxAccounts', $data['max_accounts']),
        ])->getDomain();
        $account = $client->createAccount(
            $data['domain_admin'],
            $data['admin_password'],
            [
                new Attr('zimbraIsDelegatedAdminAccount', 'TRUE'),
            ]
        )->getAccount();
        $client->grantDomainAdmin($domain->getName(), $account->getName());

        $data['zimbra_id'] = $domain->getId();
        $zimbraCreate = ZimbraAdminClient::getAttr(
            $domain, 'zimbraCreateTimestamp'
        );
        if ($zimbraCreate) {
            $data['zimbra_create'] = Carbon::createFromTimestamp(
                strtotime(intval($zimbraCreate) . 'Z')
            );
        }
        $data['attributes'] = ZimbraAdminClient::getAttrs($domain);
        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl();
    }
}
