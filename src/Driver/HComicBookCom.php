<?php

namespace Yamete\Driver;

class HComicBookCom extends Hentai4Manga
{
    const DOMAIN = 'hcomicbook.com';

    protected function getDomain()
    {
        return self::DOMAIN;
    }
}
