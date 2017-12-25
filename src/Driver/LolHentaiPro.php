<?php

namespace Yamete\Driver;

class LolHentaiPro extends ThreeDPornPics
{
    const DOMAIN = 'lolhentai.pro';

    protected function getDomain()
    {
        return self::DOMAIN;
    }

    protected function getSelector()
    {
        return '.grid-portfolio figure a';
    }
}
