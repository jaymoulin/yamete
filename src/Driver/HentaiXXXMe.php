<?php

namespace Yamete\Driver;

class HentaiXXXMe extends XXXComicPornCom
{
    private const DOMAIN = 'hentaixxx.me';

    protected function getDomain(): string
    {
        return self::DOMAIN;
    }

    protected function getSelector(): string
    {
        return '.grid-portfolio figure a';
    }
}
