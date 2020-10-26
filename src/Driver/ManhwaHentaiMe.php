<?php

namespace Yamete\Driver;

class ManhwaHentaiMe extends ManyToonCom
{
    private const DOMAIN = 'manhwahentai.me';

    protected function getDomain(): string
    {
        return self::DOMAIN;
    }
}
