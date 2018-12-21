<?php

namespace YameteTests\Driver;


class XXX3dComixCom extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'http://www.xxx3dcomix.com/gallery/artist3d-varris-animated-part-2';
        $driver = new \Yamete\Driver\XXX3dComixCom();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(12, count($driver->getDownloadables()));
    }
}
