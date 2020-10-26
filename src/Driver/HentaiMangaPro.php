<?php

namespace Yamete\Driver;

class HentaiMangaPro extends XXXComicPornCom
{
    private const DOMAIN = 'hentaimanga.pro';

    protected function getDomain(): string
    {
        return self::DOMAIN;
    }
}
