<?php

namespace YameteTests\Driver;


class Palcomix extends \PHPUnit\Framework\TestCase
{
    public function testDownload()
    {
        $url = 'http://palcomix.com/princessmarco/index.html';
        $driver = new \Yamete\Driver\Palcomix();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(45, count($driver->getDownloadables()));
    }
}
