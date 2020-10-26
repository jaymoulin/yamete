<?php

namespace Yamete\Driver;

class ComicsPornMe extends CartoonSexComixCom
{
    private const DOMAIN = 'comicsporn.me';

    protected function getDomain(): string
    {
        return self::DOMAIN;
    }

    protected function getSelector(): string
    {
        return '.grid-portfolio figure a';
    }
}
