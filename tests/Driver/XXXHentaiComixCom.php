<?php

namespace YameteTests\Driver;


class XXXHentaiComixCom extends \PHPUnit\Framework\TestCase
{
    public function testDownload()
    {
        $url = 'http://www.xxxhentaicomix.com/gallery/artist-therealshadman-part-46-1067';
        $driver = new \Yamete\Driver\XXXHentaiComixCom();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(19, count($driver->getDownloadables()));
    }
}
