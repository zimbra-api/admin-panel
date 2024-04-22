<?php declare(strict_types=1);
/**
 * This file is part of the Zimbra Admin Panel project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Enums;
use Filament\Support\Contracts\HasLabel;

/**
 * Account status enum
 *
 * @package  App
 * @category Enums
 * @author   Nguyen Van Nguyen - nguyennv1981@gmail.com
 */
enum AccountStatus: string implements HasLabel
{
    case Active      = 'active';
    case Maintenance = 'maintenance';
    case Locked      = 'locked';
    case Closed      = 'closed';
    case Lockout     = 'lockout';
    case Pending     = 'pending';

    public function getLabel(): string
    {
        return match ($this) {
            self::Active      => __('Active'),
            self::Maintenance => __('Maintenance'),
            self::Locked      => __('Locked'),
            self::Closed      => __('Closed'),
            self::Lockout     => __('Lock Out'),
            self::Pending     => __('Pending'),
        };
    }
}
