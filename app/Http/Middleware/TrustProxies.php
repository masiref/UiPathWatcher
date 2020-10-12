<?php

namespace App\Http\Middleware;

use Fideloper\Proxy\TrustProxies as Middleware;
use Illuminate\Http\Request;
use Illuminate\Contracts\Config\Repository;

class TrustProxies extends Middleware
{
    /**
     * The trusted proxies for this application.
     *
     * @var array|string
     */
    protected $proxies;

    /**
     * The headers that should be used to detect proxies.
     *
     * @var int
     */
    protected $headers = Request::HEADER_X_FORWARDED_ALL;

    public function __construct(Repository $config)
    {
        $proxies = env('TRUSTED_PROXIES', null);
        if ($proxies) {
            $this->proxies = $proxies->explode(',');
        }
        parent::__construct($config);
    }
}
