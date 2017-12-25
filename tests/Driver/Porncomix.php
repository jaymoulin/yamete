<?php

namespace YameteTests\Driver;


class Porncomix extends \PHPUnit\Framework\TestCase
{
    public function testDownload()
    {
        $url = 'http://www.porncomix.info/party-with-mom/';
        $driver = new \Yamete\Driver\Porncomix();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(28, count($driver->getDownloadables()));
    }
}
