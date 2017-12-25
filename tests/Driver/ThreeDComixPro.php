<?php

namespace YameteTests\Driver;


class ThreeDComixPro extends \PHPUnit\Framework\TestCase
{
    public function testDownload()
    {
        $url = 'http://www.3dcomix.pro/pictures/vacation-in-the-mountais-part-3';
        $driver = new \Yamete\Driver\ThreeDComixPro();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(10, count($driver->getDownloadables()));
    }
}
