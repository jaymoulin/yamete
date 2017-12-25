<?php

namespace YameteTests\Driver;


class XXXToonComicsCom extends \PHPUnit\Framework\TestCase
{
    public function testDownload()
    {
        $url = 'http://www.xxxtooncomics.com/gallery/studio-oppai-a-beautiful-day-at-beach';
        $driver = new \Yamete\Driver\XXXToonComicsCom();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(7, count($driver->getDownloadables()));
    }
}
