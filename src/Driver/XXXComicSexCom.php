<?php

namespace Yamete\Driver;

class XXXComicSexCom extends CartoonSexComixCom
{
    private const DOMAIN = 'xxxcomicsex.com';

    protected function getDomain(): string
    {
        return self::DOMAIN;
    }

    protected function getSelector(): string
    {
        return 'figure a';
    }
}
