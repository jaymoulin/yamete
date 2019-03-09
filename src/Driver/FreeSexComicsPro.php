<?php

namespace Yamete\Driver;

class FreeSexComicsPro extends XXXComicPornCom
{
    const DOMAIN = 'freesexcomics.pro';

    protected function getDomain(): string
    {
        return self::DOMAIN;
    }

    protected function getSelector(): string
    {
        return '.page-gallery figure a';
    }
}
