<?php

namespace Yamete\Driver;


class XComics4YouCom extends HentaiHighSchoolCom
{
    const DOMAIN = 'xcomics4you.com';

    protected function getDomain(): string
    {
        return self::DOMAIN;
    }
}
