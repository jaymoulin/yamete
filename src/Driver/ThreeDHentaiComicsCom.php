<?php

namespace Yamete\Driver;

class ThreeDHentaiComicsCom extends CartoonSexComixCom
{
    private const DOMAIN = '3dhentaicomics.com';

    protected function getDomain(): string
    {
        return self::DOMAIN;
    }

    protected function getSelector(): string
    {
        return 'a.img-init';
    }
}
