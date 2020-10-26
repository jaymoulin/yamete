<?php

namespace Yamete\Driver;

class SexyToonPornCom extends CartoonSexComixCom
{
    private const DOMAIN = 'sexytoonporn.com';

    protected function getDomain(): string
    {
        return self::DOMAIN;
    }

    protected function getSelector(): string
    {
        return 'a.img-init';
    }
}
