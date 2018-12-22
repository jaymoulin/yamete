<?php

namespace Yamete\Driver;


class MangEroticaCom extends HentaiHighSchoolCom
{
    const DOMAIN = 'mangerotica.com';

    protected function getDomain(): string
    {
        return self::DOMAIN;
    }
}
