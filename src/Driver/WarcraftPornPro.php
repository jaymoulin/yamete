<?php

namespace Yamete\Driver;

class WarcraftPornPro extends ThreeDPornPics
{
    const DOMAIN = 'warcraftporn.pro';

    protected function getDomain()
    {
        return self::DOMAIN;
    }
}
