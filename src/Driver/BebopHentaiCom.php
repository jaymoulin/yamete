<?php

namespace Yamete\Driver;


class BebopHentaiCom extends LuckyStarPornCom
{
    const DOMAIN = 'bebop-hentai.com';

    protected function getDomain()
    {
        return self::DOMAIN;
    }

    protected function getStart()
    {
        return 1;
    }
}
