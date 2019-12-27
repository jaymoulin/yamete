<?php

namespace Yamete\Driver;

class HentaiXXXComicsCom extends XXXHentaiComixCom
{
    const DOMAIN = 'hentaixxxcomics.com';

    protected function getDomain(): string
    {
        return self::DOMAIN;
    }
}
