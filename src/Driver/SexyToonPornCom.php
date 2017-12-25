<?php

namespace Yamete\Driver;

class SexyToonPornCom extends CartoonSexComixCom
{
    const DOMAIN = 'sexytoonporn.com';

    protected function getDomain()
    {
        return self::DOMAIN;
    }

    protected function getSelector()
    {
        return 'a.img-init';
    }
}
