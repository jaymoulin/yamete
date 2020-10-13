<?php

namespace Yamete\Driver;

class PornComixPro extends XXXComicPornCom
{
    const DOMAIN = 'porncomix.pro';

    protected function getDomain(): string
    {
        return self::DOMAIN;
    }

    protected function getSelector(): string
    {
        return '.my-gallery figure a';
    }
}
