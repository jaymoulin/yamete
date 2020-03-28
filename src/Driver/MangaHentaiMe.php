<?php

namespace Yamete\Driver;

class MangaHentaiMe extends ManyToonCom
{
    const DOMAIN = 'mangahentai.me';

    protected function getDomain(): string
    {
        return self::DOMAIN;
    }
}
