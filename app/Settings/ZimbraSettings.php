<?php declare(strict_types=1);
/**
 * This file is part of the Zimbra Multi-Tenancy Admin Panel project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

/**
 * Zimbra settings
 *
 * @package  App
 * @category Settings
 * @author   Nguyen Van Nguyen - nguyennv1981@gmail.com
 */
class ZimbraSettings extends Settings
{
    public string $serviceUrl;

    public string $adminUser;

    public string $adminPassword;

    public string $defaultDomain;

    public static function group(): string
    {
        return 'zimbra';
    }

    public static function encrypted(): array
    {
        return [
            'adminPassword',
        ];
    }
}
