<?php declare(strict_types=1);
/**
 * This file is part of the Zimbra Admin Panel project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Providers;

use App\Support\ZimbraAdminClient;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

/**
 * App service provider class
 *
 * @package  App
 * @category Providers
 * @author   Nguyen Van Nguyen - nguyennv1981@gmail.com
 */
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(ZimbraAdminClient::class, function (): ZimbraAdminClient {
            $client = ZimbraAdminClient::fromSettings();
            $client->authFromSession();
            return $client;
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if ((bool) env('FORCE_HTTPS', false)) {
            URL::forceScheme('https');
        }
    }
}
