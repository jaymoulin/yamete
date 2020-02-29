<?php


namespace Yamete\Driver;

class XLecXCom extends XCartX
{
    const DOMAIN = 'xlecx.com';

    protected function getDomain(): string
    {
        return self::DOMAIN;
    }
}
