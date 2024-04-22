<?php declare(strict_types=1);
/**
 * This file is part of the Zimbra Multi-Tenancy Admin Panel project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Enums;

/**
 * Admin panel enum
 *
 * @package  App
 * @category Enum
 * @author   Nguyen Van Nguyen - nguyennv1981@gmail.com
 */
enum AdminPanel: string
{
    case Admin  = 'admin';
    case Agency = 'agency';
    case Domain = 'domain';
    case Group  = 'group';

    public function path(): string
    {
        return match ($this) {
            self::Admin  => 'admin',
            self::Agency => 'agency',
            self::Domain => 'domain',
            self::Group  => 'group',
        };
    }
}
