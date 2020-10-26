<?php

namespace Yamete\Driver;

class HentaiXXXComicsCom extends XXXHentaiComixCom
{
    private const DOMAIN = 'hentaixxxcomics.com';

    protected function getDomain(): string
    {
        return self::DOMAIN;
    }
}
