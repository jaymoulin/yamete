<?php

namespace Yamete\Driver;

class ThreeDSexComicsPro extends CartoonSexComixCom
{
    private const DOMAIN = '3dsexcomics.pro';

    protected function getDomain(): string
    {
        return self::DOMAIN;
    }
}
