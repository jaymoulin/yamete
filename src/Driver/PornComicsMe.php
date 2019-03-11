<?php

namespace Yamete\Driver;

class PornComicsMe extends XXXComicPornCom
{
    const DOMAIN = 'porncomics.me';

    protected function getDomain(): string
    {
        return self::DOMAIN;
    }
}
