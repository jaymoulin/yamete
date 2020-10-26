<?php

namespace Yamete\Driver;

class MyMangaComics extends MyHentaiGallery
{
    private const DOMAIN = 'mymangacomics.com';

    protected function getDomain(): string
    {
        return self::DOMAIN;
    }
}
