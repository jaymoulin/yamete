<?php

namespace Yamete\Driver;

class HentaiPorn extends XXXComicPornCom
{
    const DOMAIN = 'hentaiporn.pics';

    protected function getDomain(): string
    {
        return self::DOMAIN;
    }
}
