<?php declare(strict_types=1);
/**
 * This file is part of the Zimbra Multi-Tenancy Admin Panel project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Filament\Agency\Resources\DomainResource\Pages;

use App\Filament\Agency\Resources\DomainResource;
use App\Models\Account;
use App\Models\ClassOfService;
use App\Support\ZimbraAdminClient;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Zimbra\Admin\Struct\Attr;
use Zimbra\Common\Zimbra\AccountBy;
use Zimbra\Common\Struct\AccountSelector;

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
        $data['account'] = $account;

        $data['zimbra_id'] = $domain->getId();
        $data['attributes'] = ZimbraAdminClient::getAttrs($domain);
        $zimbraCreate = ZimbraAdminClient::getAttr(
            $domain, 'zimbraCreateTimestamp'
        );
        if ($zimbraCreate) {
            $data['zimbra_create'] = Carbon::createFromTimestamp(
                strtotime(intval($zimbraCreate) . 'Z')
            );
        }
        return $data;
    }

    protected function handleRecordCreation(array $data): Model
    {
        $account = $data['account'];
        unset($data['account']);

        $record = parent::handleRecordCreation($data);

        $attrs = ZimbraAdminClient::getAttrs($account);
        $zimbraCreate = null;
        if (!empty($attrs['zimbraCreateTimestamp'])) {
            $zimbraCreate = Carbon::createFromTimestamp(
                strtotime(intval($attrs['zimbraCreateTimestamp']) . 'Z')
            );
        }
        Account::create([
            'agency_id' => $record->agency_id,
            'domain_id' => $record->id,
            'cos_id' => (int) ClassOfService::firstWhere(
                'zimbra_id', $attrs['zimbraCOSId'] ?? ''
            )->id,
            'zimbra_id' => $account->getId(),
            'name' => $account->getName(),
            'display_name' => $attrs['displayName'] ?? null,
            'mail_host' => $attrs['zimbraMailHost'] ?? null,
            'zimbra_cos_id' => $attrs['zimbraCOSId'] ?? null,
            'zimbra_create' => $zimbraCreate,
            'is_domain_admin' => true,
            'attributes' => $attrs,
        ]);

        return $record;
    }

    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? $this->getResource()::getUrl();
    }
}
