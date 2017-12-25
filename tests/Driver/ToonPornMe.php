<?php

namespace YameteTests\Driver;


class ToonPornMe extends \PHPUnit\Framework\TestCase
{
    public function testDownload()
    {
        $url = 'http://toonporn.me/content/art-fantasy-girls/index.html';
        $driver = new \Yamete\Driver\ToonPornMe();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(28, count($driver->getDownloadables()));
    }
}
