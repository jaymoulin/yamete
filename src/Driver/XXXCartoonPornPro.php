<?php

namespace Yamete\Driver;

class XXXCartoonPornPro extends CartoonPornPicNet
{
    const DOMAIN = 'xxxcartoonporn.pro';

    protected function getDomain()
    {
        return self::DOMAIN;
    }

    protected function getSelector()
    {
        return '.page-gallery figure a';
    }
}
