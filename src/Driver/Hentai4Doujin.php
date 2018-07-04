<?php

namespace Yamete\Driver;

class Hentai4Doujin extends Hentai4Manga
{
    const DOMAIN = 'hentai4doujin.com';

    protected function getDomain()
    {
        return self::DOMAIN;
    }
}
