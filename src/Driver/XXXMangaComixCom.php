<?php

namespace Yamete\Driver;

class XXXMangaComixCom extends XXXHentaiComixCom
{
    const DOMAIN = 'xxxmangacomix.com';

    protected function getDomain(): string
    {
        return self::DOMAIN;
    }
}
