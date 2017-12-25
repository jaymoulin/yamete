<?php

namespace YameteTests\Driver;


class AnimeSexyPicsCom extends \PHPUnit\Framework\TestCase
{
    public function testDownload()
    {
        $url = 'http://animesexypics.com/gallery/pregnant-hentai-sex/index.html';
        $driver = new \Yamete\Driver\AnimeSexyPicsCom();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(5, count($driver->getDownloadables()));
    }
}
