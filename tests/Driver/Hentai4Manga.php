<?php

namespace YameteTests\Driver;


class Hentai4Manga extends \PHPUnit\Framework\TestCase
{
    public function testDownload()
    {
        $url = 'http://hentai4manga.com/hentai_manga/Yuuki-Shin-Hide-and-Seek-COMIC-Kairakuten-BEAST-2012-06-Thai---H_27418/';
        $driver = new \Yamete\Driver\Hentai4Manga();
        $driver->setUrl($url);
        $this->assertNotFalse($driver->canHandle());
        $this->assertEquals(17, count($driver->getDownloadables()));
    }
}
