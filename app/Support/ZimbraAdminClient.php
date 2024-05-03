<?php declare(strict_types=1);
/**
 * This file is part of the Zimbra Multi-Tenancy Admin Panel project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Support;

use App\Settings\ZimbraSettings;
use Illuminate\Support\Traits\ForwardsCalls;
use PsrDiscovery\Entities\CandidateEntity;
use PsrDiscovery\Implementations\Psr18\Clients;
use Zimbra\Admin\AdminApi;
use Zimbra\Admin\Message\GetAllServersResponse;
use Zimbra\Admin\Struct\AdminObjectInterface;
use Zimbra\Admin\Struct\Attr;

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

    const SESSION_AUTH_TOKEN_KEY = 'zimbra-auth-token';

    private readonly AdminApi $api;

    public function __construct(string $serviceUrl)
    {
        if (config('app.debug')) {
            Clients::add(CandidateEntity::create(
                package: 'guzzlehttp/guzzle',
                version: '^7.0',
                builder: static fn () => new \GuzzleHttp\Client([
                    'verify' => false,
                ]),
            ));
        }
        $this->api = new AdminApi($serviceUrl);
        $this->api->setLogger(logger());
    }

    public static function fromSettings(bool $single = false): self
    {
        $settings = app(ZimbraSettings::class);
        return $single ? once(fn () => new self($settings->serviceUrl)) :
               new self($settings->serviceUrl);
    }

    public static function getAttrs(AdminObjectInterface $objectInfo): array
    {
        static $data = [];
        $id = $objectInfo->getId();
        if (isset($data[$id])) {
            return $data[$id];
        }
        $attrs = [];
        foreach ($objectInfo->getAttrList() as $attr) {
            $attrKey = $attr->getKey();
            $attrValue = $attr->getValue();
            if (!isset($attrs[$attrKey])) {
                $attrs[$attrKey] = $attrValue;
            }
            else {
                if (!is_array($attrs[$attrKey])) {
                    $attrs[$attrKey] = [$attrs[$attrKey]];
                }
                $attrs[$attrKey][] = $attrValue;
            }
        }
        $data[$id] = $attrs;
        return $attrs;
    }

    public static function getAttr(
        AdminObjectInterface $objectInfo, string $key, $default = null
    )
    {
        $attrs = self::getAttrs($objectInfo);
        return $attrs[$key] ?? $default;
    }

    public function authFromSession(): string
    {
        return rescue(function () {
            return $this->api->authByToken(
                session(self::SESSION_AUTH_TOKEN_KEY) ?: ''
            )->getAuthToken();
        }, function () {
            $settings = app(ZimbraSettings::class);
            $authToken = $this->api->auth(
                $settings->adminUser, $settings->adminPassword
            )->getAuthToken();
            session([
                self::SESSION_AUTH_TOKEN_KEY => $authToken,
            ]);
            return $authToken;
        });
    }

    /**
     * Get global config by name
     * 
     * @param  string $configName
     * @return array
     */
    public function getConfigByName(string $configName): array
    {
        $values = [];
        $attrs = $this->api->getConfig(new Attr($configName))->getAttrs();
        foreach ($attrs as $attr) {
            if ($attr->getKey() === $configName) {
                $values[] = $attr->getValue();
            }
        }
        return $values;
    }

    /**
     * Get all mailbox servers defined in the system.
     * 
     * @param  string $alwaysOnClusterId
     * @param  bool $applyConfig
     * @return array
     */
    public function getAllMailboxServers(
        ?string $alwaysOnClusterId = NULL, ?bool $applyConfig = NULL
    ): array
    {
        return $this->api->getAllServers(
            'mailbox', $alwaysOnClusterId, $applyConfig
        )?->getServerList() ?? [];
    }

    public function __call(string $method, array $parameters): mixed
    {
        return $this->forwardCallTo($this->api, $method, $parameters);
    }
}
