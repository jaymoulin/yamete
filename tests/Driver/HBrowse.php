<?php

namespace YameteTests\Driver;


class HBrowse extends \PHPUnit\Framework\TestCase
{
    public function testDownload()
    {
        $url = 'http://www.hbrowse.com/13537/c00001';
        $driver = new \Yamete\Driver\HBrowse();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(18, count($driver->getDownloadables()));
    }
}
