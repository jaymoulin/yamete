<?php

namespace YameteTests\Driver;


class LoveHentaiManga extends \PHPUnit\Framework\TestCase
{
    public function testDownload()
    {
        $url = 'http://lovehentaimanga.com/hentai_manga/index.php/gadgirl/gadgirl';
        $driver = new \Yamete\Driver\LoveHentaiManga();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(29, count($driver->getDownloadables()));
    }
}
