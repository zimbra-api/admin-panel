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
use App\Models\DomainCos;
use App\Zimbra\AdminClient;
use Filament\Forms\{
    Form,
    Get,
    Set,
};
use Filament\Forms\Components\{
    Grid,
    Hidden,
    Select,
    Textarea,
    TextInput,
};
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Zimbra\Admin\Struct\Attr;

class CreateDomain extends CreateRecord
{
    protected static string $resource = DomainResource::class;
    protected static bool $canCreateAnother = false;

    public function form(Form $form): Form
    {
        return $form->schema([
            Grid::make(3)->schema([
                TextInput::make('name')
                    ->rules([
                        fn () => function (string $attribute, $value, \Closure $fail) {
                            if (!filter_var($value, FILTER_VALIDATE_DOMAIN)) {
                                $fail(__('The domain name is invalid.'));
                            }
                        },
                    ])
                    ->required()->unique()->label(__('Name')),
                TextInput::make('domain_admin')->email()
                    ->rules([
                        fn (Get $get) => function (
                            string $attribute, $value, \Closure $fail
                        ) use ($get) {
                            if (!Str::endsWith($value, $get('name'))) {
                                $fail(__('The email address must match the domain name.'));
                            }
                        },
                    ])
                    ->required()->label(__('Domain Admin')),
                TextInput::make('admin_password')->password()
                    ->required()->label(__('Admin Password')),
            ]),
            Grid::make(3)->schema([
                Select::make('status')->options(DomainStatus::class)
                    ->required()->label(__('Status')),
                Select::make('coses')
                    ->options(
                        auth()->user()->agency->coses->pluck('name', 'id')
                    )->live()->multiple()
                    ->afterStateUpdated(function (Set $set, array $state) {
                        $set(
                            'max_accounts',
                            ClassOfService::find($state)->sum('max_accounts')
                        );
                    })
                    ->required()->label(__('Class Of Services')),
                TextInput::make('max_accounts')
                    ->default(0)->minValue(0)->readonly()
                    ->required()->label(__('Max Accounts')),
            ]),
            Textarea::make('description')->columnSpan(2)
                ->label(__('Description')),
            Hidden::make('agency_id')->default(auth()->user()->agency->id),
            Hidden::make('zimbra_id'),
        ]);
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $client = app(AdminClient::class);
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
        $data['attributes'] = $attrs = AdminClient::getAttrs($domain);
        if (!empty($attrs['zimbraCreateTimestamp'])) {
            $data['zimbra_create'] = Carbon::createFromTimestamp(
                strtotime(intval($attrs['zimbraCreateTimestamp']) . 'Z')
            );
        }
        unset($data['admin_password']);
        return $data;
    }

    protected function handleRecordCreation(array $data): Model
    {
        $account = $data['account'];
        unset($data['account']);

        $record = parent::handleRecordCreation($data);
        if (!empty($data['coses'])) {
            foreach ($data['coses'] as $cos_id) {
                DomainCos::create([
                    'domain_id' => $record->id,
                    'cos_id' => $cos_id,
                ]);
            }
        }

        $attrs = AdminClient::getAttrs($account);
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
