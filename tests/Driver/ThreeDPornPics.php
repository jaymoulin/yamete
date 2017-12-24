<?php

namespace YameteTests\Driver;


class ThreeDPornPics extends \PHPUnit\Framework\TestCase
{
    public function testDownload()
    {
        $url = 'http://www.3dpornpics.pro/galleries/spoov-bus-stop';
        $driver = new \Yamete\Driver\ThreeDPornPics();
        $driver->setUrl($url);
        $this->assertNotFalse($driver->canHandle());
        $this->assertEquals(6, count($driver->getDownloadables()));
    }
}
