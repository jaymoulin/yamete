<?php

namespace Yamete\Driver;

class MyMangaComics extends MyHentaiGallery
{
    const DOMAIN = 'mymangacomics.com';

    protected function getDomain(): string
    {
        return self::DOMAIN;
    }
}
