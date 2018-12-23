<?php

namespace Yamete\Driver;


class BebopHentaiCom extends FullMetalHentaiCom
{
    const DOMAIN = 'bebop-hentai.com';

    protected function getDomain(): string
    {
        return self::DOMAIN;
    }

    protected function getStart(): int
    {
        return 1;
    }
}
