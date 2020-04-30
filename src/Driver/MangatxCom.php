<?php

namespace Yamete\Driver;

use GuzzleCloudflare\Middleware;
use GuzzleHttp\Cookie\FileCookieJar;

class MangatxCom extends IsekaiScanCom
{
    const DOMAIN = 'mangatx.com';

    protected function getDomain(): string
    {
        return self::DOMAIN;
    }
}
