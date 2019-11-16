<?php

namespace Yamete\Driver;

class HComic1Com extends HComicIn
{
    const DOMAIN = 'hcomic1.com';

    protected function getDomain(): string
    {
        return self::DOMAIN;
    }
}
