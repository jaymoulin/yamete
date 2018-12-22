<?php

namespace Yamete\Driver;


class FullMetalHentaiCom extends HighHentaiCom
{
    const DOMAIN = 'fullmetal-hentai.com';

    protected function getDomain(): string
    {
        return self::DOMAIN;
    }
}
