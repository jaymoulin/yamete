<?php

namespace Yamete\Driver;

class FreeSexComixPro extends ThreeDPornPics
{
    const DOMAIN = 'freesexcomix.pro';

    protected function getDomain()
    {
        return self::DOMAIN;
    }

    protected function getSelector()
    {
        return '.page-gallery figure a';
    }
}
