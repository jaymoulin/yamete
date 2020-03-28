<?php

namespace Yamete\Driver;

class BoysLoveMe extends ManyToonCom
{
    const DOMAIN = 'boyslove.me';

    protected function getDomain(): string
    {
        return self::DOMAIN;
    }
}
