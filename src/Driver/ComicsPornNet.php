<?php

namespace Yamete\Driver;

class ComicsPornNet extends XXXComicPornCom
{
    private const DOMAIN = 'comicsporn.net';

    protected function getDomain(): string
    {
        return self::DOMAIN;
    }
}
