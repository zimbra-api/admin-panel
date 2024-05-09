<?php declare(strict_types=1);
/**
 * This file is part of the Zimbra Multi-Tenancy Admin Panel project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Auth;

use Illuminate\Auth\GenericUser;

/**
 * Zimbra user
 *
 * @package  App
 * @category Auth
 * @author   Nguyen Van Nguyen - nguyennv1981@gmail.com
 */
class ZimbraUser extends GenericUser
{
    public function getAuthIdentifierName()
    {
        return 'name';
    }
}
