<?php

namespace YameteTests\Driver;


use PHPUnit\Framework\TestCase;

class OverwatchPornPro extends TestCase
{
    public function testDownload()
    {
        $url = 'http://www.overwatchporn.pro/galleries/friend-request-3259';
        $driver = new \Yamete\Driver\OverwatchPornPro();
        $driver->setUrl($url);
        $this->assertNotFalse($driver->canHandle());
        $this->assertEquals(9, count($driver->getDownloadables()));
    }
}
