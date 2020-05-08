<?php

namespace Yamete\Driver;

class ZinmangaCom extends IsekaiScanCom
{
    const DOMAIN = 'zinmanga.com';

    protected function getDomain(): string
    {
        return self::DOMAIN;
    }
}
