<?php

namespace Yamete\Driver;

class ThreeDSexComicsPro extends CartoonSexComixCom
{
    const DOMAIN = '3dsexcomics.pro';

    protected function getDomain()
    {
        return self::DOMAIN;
    }
}
