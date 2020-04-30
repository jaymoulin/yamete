<?php

namespace Yamete\Driver;

class MangaBobCom extends IsekaiScanCom
{
    const DOMAIN = 'mangabob.com';

    protected function getDomain(): string
    {
        return self::DOMAIN;
    }
}
