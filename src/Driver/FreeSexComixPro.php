<?php

namespace Yamete\Driver;

class FreeSexComixPro extends XXXComicPornCom
{
    const DOMAIN = 'freesexcomix.pro';

    protected function getDomain(): string
    {
        return self::DOMAIN;
    }

    protected function getSelector(): string
    {
        return '.page-gallery figure a';
    }
}
