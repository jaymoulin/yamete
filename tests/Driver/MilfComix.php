<?php

namespace YameteTests\Driver;


class MilfComix extends \PHPUnit\Framework\TestCase
{
    public function testDownload()
    {
        $url = 'https://milfcomix.com/sweet-sting-part-2-doxy/';
        $driver = new \Yamete\Driver\MilfComix();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(17, count($driver->getDownloadables()));
    }
}
