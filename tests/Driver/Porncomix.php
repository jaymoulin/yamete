<?php

namespace YameteTests\Driver;


class Porncomix extends \PHPUnit\Framework\TestCase
{
    public function testDownload()
    {
        $url = 'http://www.porncomix.info/party-with-mom/';
        $driver = new \Yamete\Driver\Porncomix();
        $driver->setUrl($url);
        $this->assertNotFalse($driver->canHandle());
        $this->assertEquals(30, count($driver->getDownloadables()));
    }
}
