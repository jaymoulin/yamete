<?php

namespace Yamete\Driver;

class HentaiXXXComicsCom extends XXXHentaiPicsPro
{
    private const DOMAIN = 'hentaixxxcomics.com';

    protected function getDomain(): string
    {
        return self::DOMAIN;
    }
}
