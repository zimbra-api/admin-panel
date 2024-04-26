<?php declare(strict_types=1);
/**
 * This file is part of the Zimbra Multi-Tenancy Admin Panel project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Support;

use Illuminate\Support\Traits\ForwardsCalls;
use PsrDiscovery\Entities\CandidateEntity;
use PsrDiscovery\Implementations\Psr18\Clients;
use Zimbra\Admin\AdminApi;

/**
 * Zimbra admin client
 *
 * @package  App
 * @category Support
 * @author   Nguyen Van Nguyen - nguyennv1981@gmail.com
 */
class ZimbraAdminClient
{
    use ForwardsCalls;

    private readonly AdminApi $api;

    public function __construct(string $serviceUrl)
    {
        Clients::add(CandidateEntity::create(
            package: 'guzzlehttp/guzzle',
            version: '^7.0',
            builder: static fn () => new \GuzzleHttp\Client([
                'verify' => !config('app.debug'),
            ]),
        ));
        $this->api = new AdminApi($serviceUrl);
        $this->api->setLogger(logger());
    }

    public function __call(string $method, array $parameters): mixed
    {
        return $this->forwardCallTo($this->api, $method, $parameters);
    }
}
