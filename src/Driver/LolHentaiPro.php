<?php

namespace Yamete\Driver;

class LolHentaiPro extends XXXComicPornCom
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
