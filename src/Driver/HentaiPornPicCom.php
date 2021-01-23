<?php

namespace Yamete\Driver;

class HentaiPornPicCom extends XXXComicPornCom
{
    private const DOMAIN = 'hentaipornpic.com';

    protected function getDomain(): string
    {
        return self::DOMAIN;
    }

    protected function getSelector(): string
    {
        return '#wall figure a';
    }
}
