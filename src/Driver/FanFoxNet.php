<?php

namespace Yamete\Driver;

class FanFoxNet extends MangaHereCc
{
    const DOMAIN = 'fanfox.net';

    /**
     * @return string
     */
    protected function getDomain(): string
    {
        return self::DOMAIN;
    }
}
