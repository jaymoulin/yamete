<?php

namespace Yamete\Driver;

class MangaRockTeamCom extends IsekaiScanCom
{
    const DOMAIN = 'mangarockteam.com';

    protected function getDomain(): string
    {
        return self::DOMAIN;
    }
}
