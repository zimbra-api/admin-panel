<?php declare(strict_types=1);
/**
 * This file is part of the Zimbra Multi-Tenancy Admin Panel project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Auth;

use App\Models\Account;
use App\Zimbra\AdminClient;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider;
use Zimbra\Common\Zimbra\AccountBy;
use Zimbra\Common\Struct\AccountSelector;

/**
 * Zimbra user provider
 *
 * @package  App
 * @category Auth
 * @author   Nguyen Van Nguyen - nguyennv1981@gmail.com
 */
class ZimbraUserProvider implements UserProvider
{
    const SESSION_AUTH_TOKEN_KEY = 'user-auth-token';

    private readonly AdminClient $client;

    public function __construct()
    {
        $this->client = AdminClient::fromSettings();
    }

    /**
     * {@inheritdoc}
     */
    public function retrieveById($identifier)
    {
        if ($account = Account::firstWhere('name', $identifier)) {
            return new ZimbraUser([
                'id' => $account->zimbra_id,
                'name' => $account->name,
                ...$account->attributes,
            ]);
        };
    }

    /**
     * {@inheritdoc}
     */
    public function retrieveByToken($identifier, $token)
    {
        try {
            $this->client->authByToken($token);
            $account = $this->client->getAccount(
                new AccountSelector(AccountBy::NAME, $identifier)
            )->getAccount();
            return new ZimbraUser([
                'id' => $account->getId(),
                'name' => $account->getName(),
                ...AdminClient::getAttrs($account),
            ]);
        }
        catch (\Throwable $e) {
            logger()->error($e);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function updateRememberToken(Authenticatable $user, $token)
    {
        $user->setRememberToken($token);
        session([
            self::SESSION_AUTH_TOKEN_KEY => $token,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function retrieveByCredentials(array $credentials)
    {
        $credentials = array_filter(
            $credentials,
            fn ($key) => ! str_contains($key, 'password'),
            ARRAY_FILTER_USE_KEY
        );

        if (empty($credentials)) {
            return;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function validateCredentials(Authenticatable $user, array $credentials)
    {
        if (is_null($password = $credentials['password'])) {
            return false;
        }
        try {
            $authToken = $this->client->auth(
                $user->getAuthIdentifier(), $password
            )->getAuthToken();
            session([
                self::SESSION_AUTH_TOKEN_KEY => $authToken,
            ]);
            return true;
        }
        catch (\Throwable $e) {
            logger()->error($e);
            return false;            
        }
    }

    /**
     * {@inheritdoc}
     */
    public function rehashPasswordIfRequired(Authenticatable $user, array $credentials, bool $force = false)
    {
    }
}
