<?php

namespace Yamete\Driver;

class XXXToonComicsCom extends XXXHentaiComixCom
{
    private const DOMAIN = 'xxxtooncomics.com';

    protected function getDomain(): string
    {
        return self::DOMAIN;
    }
}
