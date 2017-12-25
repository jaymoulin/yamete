<?php

namespace YameteTests\Driver;


class Erolord extends \PHPUnit\Framework\TestCase
{
    public function testDownload()
    {
        $url = 'http://erolord.com/doujin/2177314/';
        $driver = new \Yamete\Driver\Erolord();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(10, count($driver->getDownloadables()));
    }
}
