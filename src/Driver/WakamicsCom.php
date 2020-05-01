<?php

namespace Yamete\Driver;

class WakamicsCom extends IsekaiScanCom
{
    const DOMAIN = 'wakamics.com';

    protected function getDomain(): string
    {
        return self::DOMAIN;
    }
}
