<?php

namespace Yamete\Driver;

class MangatxCom extends IsekaiScanCom
{
    const DOMAIN = 'mangatx.com';

    protected function getDomain(): string
    {
        return self::DOMAIN;
    }
}
