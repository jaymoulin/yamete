<?php

namespace Yamete\Driver;

class ComicsPornPro extends XXXComicPornCom
{
    private const DOMAIN = 'comicsporn.pro';

    protected function getDomain(): string
    {
        return self::DOMAIN;
    }

    protected function getSelector(): string
    {
        return '.gallery-thumbs figure a';
    }
}
