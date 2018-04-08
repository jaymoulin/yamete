<?php

namespace YameteTests\Driver;


class Perveden extends \PHPUnit\Framework\TestCase
{
    public function testDownload()
    {
        $url = 'http://www.perveden.com/en/en-manga/nikuyoku-analyze/1/1/';
        $driver = new \Yamete\Driver\Perveden();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(24, count($driver->getDownloadables()));
    }
}
