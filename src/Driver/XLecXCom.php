<?php


namespace Yamete\Driver;

class XLecXCom extends XCartX
{
    private const DOMAIN = 'xlecx.com';

    protected function getDomain(): string
    {
        return self::DOMAIN;
    }
}
