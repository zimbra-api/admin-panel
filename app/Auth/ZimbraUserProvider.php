<?php declare(strict_types=1);
/**
 * This file is part of the Zimbra Multi-Tenancy Admin Panel project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Auth;

use App\Support\ZimbraAdminClient;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider;

/**
 * Zimbra user provider
 *
 * @package  App
 * @category Auth
 * @author   Nguyen Van Nguyen - nguyennv1981@gmail.com
 */
class ZimbraUserProvider implements UserProvider
{
    private readonly ZimbraAdminClient $client;

    public function __construct()
    {
        $this->client = app(ZimbraAdminClient::class);
    }

    /**
     * {@inheritdoc}
     */
    public function retrieveById($identifier)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function retrieveByToken($identifier, $token)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function updateRememberToken(Authenticatable $user, $token)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function retrieveByCredentials(array $credentials)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function validateCredentials(Authenticatable $user, array $credentials)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function rehashPasswordIfRequired(Authenticatable $user, array $credentials, bool $force = false)
    {
    }
}
