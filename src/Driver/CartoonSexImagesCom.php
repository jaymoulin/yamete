<?php

namespace Yamete\Driver;

class CartoonSexImagesCom extends CartoonPornPicNet
{
    const DOMAIN = 'cartoonseximages.com';

    protected function getDomain()
    {
        return self::DOMAIN;
    }

    protected function getSelector()
    {
        return '.full-gallery figure a';
    }
}
