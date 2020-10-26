<?php

namespace Yamete\Driver;

class FreewebtooncoinsCom extends ManyToonCom
{
    private const DOMAIN = 'freewebtooncoins.com';

    protected function getDomain(): string
    {
        return self::DOMAIN;
    }
}
