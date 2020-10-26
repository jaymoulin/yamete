<?php

namespace Yamete\Driver;

class HComicBookCom extends Hentai4Manga
{
    private const DOMAIN = 'hcomicbook.com';

    protected function getDomain(): string
    {
        return self::DOMAIN;
    }
}
