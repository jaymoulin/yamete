<?php

namespace Yamete\Driver;

class HentaiPorn extends XXXComicPornCom
{
    private const DOMAIN = 'hentaiporn.pics';

    protected function getDomain(): string
    {
        return self::DOMAIN;
    }
}
