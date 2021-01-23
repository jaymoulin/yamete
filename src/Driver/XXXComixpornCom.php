<?php

namespace Yamete\Driver;

class XXXComixpornCom extends XXXComicPornCom
{
    private const DOMAIN = 'xxxcomixporn.com';

    protected function getDomain(): string
    {
        return self::DOMAIN;
    }

    protected function getSelector(): string
    {
        return '#wall figure a';
    }
}
