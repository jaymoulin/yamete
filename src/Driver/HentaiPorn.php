<?php

namespace Yamete\Driver;

class HentaiPorn extends ThreeDPornPics
{
    const DOMAIN = 'hentaiporn.pics';

    protected function getDomain()
    {
        return self::DOMAIN;
    }
}
