<?php

namespace YameteTests\Driver;


class MilfComix extends \PHPUnit\Framework\TestCase
{
    public function testDownload()
    {
        $url = 'https://milfcomix.com/breaking-the-rules-5-the-fairly-oddparents-croc/';
        $driver = new \Yamete\Driver\MilfComix();
        $driver->setUrl($url);
        $this->assertNotFalse($driver->canHandle());
        $this->assertEquals(11, count($driver->getDownloadables()));
    }
}
