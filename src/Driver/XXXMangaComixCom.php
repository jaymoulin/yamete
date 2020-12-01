<?php

namespace Yamete\Driver;

class XXXMangaComixCom extends XXXHentaiPicsPro
{
    private const DOMAIN = 'xxxmangacomix.com';

    protected function getDomain(): string
    {
        return self::DOMAIN;
    }
}
