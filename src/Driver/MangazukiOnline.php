<?php

namespace Yamete\Driver;

class MangazukiOnline extends MangazukiMe
{
    const DOMAIN = 'mangazuki.online';

    public function getDomain(): string
    {
        return self::DOMAIN;
    }
}
