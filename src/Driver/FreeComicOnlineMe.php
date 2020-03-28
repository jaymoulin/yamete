<?php

namespace Yamete\Driver;

class FreeComicOnlineMe extends ManyToonCom
{
    const DOMAIN = 'freecomiconline.me';

    protected function getDomain(): string
    {
        return self::DOMAIN;
    }
}
