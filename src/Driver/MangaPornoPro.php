<?php

namespace Yamete\Driver;

class MangaPornoPro extends XXXComicPornCom
{
    const DOMAIN = 'mangaporno.pro';

    protected function getDomain(): string
    {
        return self::DOMAIN;
    }
}
