<?php

namespace YameteTests\Driver;


class XXXToonComicsCom extends \PHPUnit\Framework\TestCase
{
    public function testDownload()
    {
        $url = 'http://www.xxxtooncomics.com/gallery/football-and-beer-part-1.html';
        $driver = new \Yamete\Driver\XXXToonComicsCom();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(13, count($driver->getDownloadables()));
    }
}
