<?php

namespace Yamete\Driver;

class FreeComicOnlineMe extends MangaHentaiMe
{
    const DOMAIN = 'freecomiconline.me';

    /**
     * @return string
     */
    protected function getDomain(): string
    {
        return self::DOMAIN;
    }
}
