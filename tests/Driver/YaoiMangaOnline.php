<?php

namespace YameteTests\Driver;


class YaoiMangaOnline extends \PHPUnit\Framework\TestCase
{
    public function testDownload()
    {
        $url = 'http://yaoimangaonline.com/shingeki-no-kyojin-dj-shitataru-ase-no-itteki-made-omty-makino-jp/';
        $driver = new \Yamete\Driver\YaoiMangaOnline();
        $driver->setUrl($url);
        $this->assertNotFalse($driver->canHandle());
        $this->assertEquals(34, count($driver->getDownloadables()));
    }
}
