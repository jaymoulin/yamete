<?php

namespace YameteTests\Driver;


class ToonSexPics extends \PHPUnit\Framework\TestCase
{
    public function testDownload()
    {
        $url = 'http://www.toonsex.pics/galleries/bus-stop-spoov';
        $driver = new \Yamete\Driver\ToonSexCom();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(1, count($driver->getDownloadables()));
    }
}
