<?php

namespace Yamete\Driver;

class Sexy3dComixCom extends CartoonSexComixCom
{
    const DOMAIN = 'sexy3dcomix.com';

    protected function getDomain()
    {
        return self::DOMAIN;
    }

    protected function getSelector()
    {
        return '.gallery-thumbs figure a';
    }
}
