<?php

namespace YameteTests\Driver;


class HentaiFromHell extends \PHPUnit\Framework\TestCase
{
    public function testDownload()
    {
        $url = 'http://hentaifromhell.org/shouchuu-mac-hozumi-kenji-d-mode-dragon-quest-xi/';
        $driver = new \Yamete\Driver\HentaiFromHell();
        $driver->setUrl($url);
        $this->assertNotFalse($driver->canHandle());
        $this->assertEquals(30, count($driver->getDownloadables()));
    }
}
