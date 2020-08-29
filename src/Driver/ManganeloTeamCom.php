<?php

namespace Yamete\Driver;

class ManganeloTeamCom extends IsekaiScanCom
{
    const DOMAIN = 'manganeloteam.com';

    protected function getDomain(): string
    {
        return self::DOMAIN;
    }

    protected function getRegexp(): string
    {
        return '~src="([^"]+)" class="wp-manga-chapter-img~';
    }

    protected function getChapterRule(): string
    {
        return '.wp-manga-chapter a';
    }
}
