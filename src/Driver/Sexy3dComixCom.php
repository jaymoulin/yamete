<?php

namespace Yamete\Driver;

class Sexy3dComixCom extends CartoonSexComixCom
{
    const DOMAIN = 'sexy3dcomix.com';

    protected function getDomain(): string
    {
        return self::DOMAIN;
    }

    protected function getSelector(): string
    {
        return '.gallery-thumbs figure a';
    }
}
