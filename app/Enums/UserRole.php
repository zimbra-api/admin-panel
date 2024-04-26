<?php declare(strict_types=1);
/**
 * This file is part of the Zimbra Multi-Tenancy Admin Panel project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

/**
 * User role enum
 *
 * @package  App
 * @category Enums
 * @author   Nguyen Van Nguyen - nguyennv1981@gmail.com
 */
enum UserRole: string implements HasLabel
{
    case Administrator = 'administrator';
    case Agency        = 'agency';
    case DomainAdmin   = 'domain-admin';
    case GroupAdmin    = 'group-admin';

    public function getLabel(): string
    {
        return match ($this) {
            self::Administrator => __('Administrator'),
            self::Agency        => __('Agency'),
            self::DomainAdmin   => __('Domain Admin'),
            self::GroupAdmin    => __('Group Admin'),
        };
    }
}
