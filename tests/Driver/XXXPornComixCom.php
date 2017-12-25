<?php

namespace YameteTests\Driver;


class XXXPornComixCom extends \PHPUnit\Framework\TestCase
{
    public function testDownload()
    {
        $url = 'http://www.xxxporncomix.com/gallery/naru-x-hina-x-saku-naruto-jay-marvello';
        $driver = new \Yamete\Driver\XXXPornComixCom();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(7, count($driver->getDownloadables()));
    }
}
