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
 * Panel settings
 *
 * @package  App
 * @category Settings
 * @author   Nguyen Van Nguyen - nguyennv1981@gmail.com
 */
class PanelSettings extends Settings
{
    public string $id;
    public string $path;
    public string $favicon;
    public bool $spaMode;
    public string $brandName;
    public string $brandLogo;

    public static function group(): string
    {
        return 'panel';
    }
}
