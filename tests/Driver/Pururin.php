<?php

namespace YameteTests\Driver;


class Pururin extends \PHPUnit\Framework\TestCase
{
    public function testDownload()
    {
        $url = 'http://pururin.us/gallery/35297/kyouei-swimming';
        $driver = new \Yamete\Driver\Pururin();
        $driver->setUrl($url);
        $this->assertNotFalse($driver->canHandle());
        $this->assertEquals(9, count($driver->getDownloadables()));
    }
}
