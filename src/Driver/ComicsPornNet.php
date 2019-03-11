<?php

namespace Yamete\Driver;

class ComicsPornNet extends XXXComicPornCom
{
    const DOMAIN = 'comicsporn.net';

    protected function getDomain(): string
    {
        return self::DOMAIN;
    }
}
