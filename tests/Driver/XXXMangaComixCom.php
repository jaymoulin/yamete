<?php

namespace YameteTests\Driver;


class XXXMangaComixCom extends \PHPUnit\Framework\TestCase
{
    public function testDownload()
    {
        $url = 'http://www.xxxmangacomix.com/gallery/declaration-of-obedience-hentai';
        $driver = new \Yamete\Driver\XXXMangaComixCom();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(8, count($driver->getDownloadables()));
    }
}
